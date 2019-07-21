<?php

function login($email, $pass, $autologin, $mobile = false) 
{
	global $settings;
	$pass = md5($pass);

	$user = db_row('SELECT *, "org" AS usertype FROM cms_users WHERE email != "" AND email = :email AND (NOT deleted OR deleted IS NULL)', array('email' => $email));

	if ($user) { #e-mailaddress exists in database
		if ($user['pass'] == $pass) { # password is correct
	
			# Check if account is not expired
			$in_valid_dates = check_valid_from_until_date($user['valid_firstday'], $user['valid_lastday']);
			$success = $in_valid_dates['success'];
			$message = $in_valid_dates['message'];
	
			if ($success) {
				loadSessionData($user);
	
				db_query('UPDATE cms_users SET lastlogin = NOW(), lastaction = NOW() WHERE id = :id', array('id' => $_SESSION['user']['id']));
				logfile(($mobile ? 'Mobile user ' :'User ').'logged in with ' . $_SERVER['HTTP_USER_AGENT']);
	
				if (isset($autologin)) {
					setcookie("autologin_user", $email, time() + (3600 * 24 * 90), '/');
					setcookie("autologin_pass", $pass, time() + (3600 * 24 * 90), '/');
				} else {
					setcookie("autologin_user", null, time() - 3600, '/');
					setcookie("autologin_pass", null, time() - 3600, '/');
				}
				
				$redirect = $settings['rootdir'] . '/?action=start';
			}
		} else { # password is not correct
			$success = false;
			$message = INCORRECT_LOGIN_ERROR;
			$redirect = false;
			logfile('Attempt to login with '.($mobile ? 'mobile and ' :'').' wrong password for ' . $email);
		}
	} else { # user not found
		$success = false;
		$redirect = false;
		$deleted = db_value('SELECT email FROM cms_users WHERE email != "" AND email LIKE "'.$_POST['email'].'%" AND deleted Limit 1');
		if ($deleted) {
			$message = GENERIC_LOGIN_ERROR;
			logfile('Attempt to login '.($mobile ? 'with mobile ' :'').'as deleted user ' . $email);
		} else {
			$message = GENERIC_LOGIN_ERROR;
			logfile('Attempt to login '.($mobile ? 'with mobile ' :'').'as unknown user ' . $email);
		}
	}

	return(array("success" => $success, 'message' => $message, 'redirect' => $redirect));
}

function checksession()
{
	global $settings;
	$result = array('success' => true);

	if (isset($_SESSION['user'])) { # a valid session exists

		db_query('UPDATE cms_users SET lastaction = NOW() WHERE id = :id', array('id' => $_SESSION['user']['id']));
		$_SESSION['user'] = db_row('SELECT * FROM cms_users WHERE id = :id', array('id' => $_SESSION['user']['id']));

		# Check if account is not expired
		$in_valid_dates = check_valid_from_until_date($row['valid_firstday'], $row['valid_lastday']);
		if (!$in_valid_dates['success']) {
			$result['success'] = false;
			$result['redirect'] =  $settings['rootdir'] . $settings['cmsdir'] . '/login.php?destination=' . urlencode($_SERVER['REQUEST_URI']);
			$result['message'] = $in_valid_dates['message'];
		}
	} else { # no valid session exists
		if (isset($_COOKIE['autologin_user'])) { # a autologin cookie exists
			$user = db_row('SELECT * FROM cms_users WHERE email != "" AND email = :email AND pass = :pass', array('email' => $_COOKIE['autologin_user'], 'pass' => $_COOKIE['autologin_pass']));
			if ($user) {
				loadSessionData($user);
				db_query('UPDATE cms_users SET lastlogin = NOW(), lastaction = NOW() WHERE id = :id', array('id' => $_SESSION['user']['id']));
			}
		} else {
			$result['success'] = false;
			$result['redirect'] =  $settings['rootdir'] . $settings['cmsdir'] . '/login.php?destination=' . urlencode($_SERVER['REQUEST_URI']);
		}
	}
	return $result;
}

function logout($redirect = false)
{
	global $settings;

	db_query('UPDATE cms_users SET lastaction = "0000-00-00 00:00:00" WHERE id = :id', array('id' => $_SESSION['user']['id']));

	setcookie("autologin_user", null, time() - 3600, '/');
	setcookie("autologin_pass", null, time() - 3600, '/');

	session_unset();
	session_destroy();

	if (!$redirect) $redirect = '?';
	redirect($redirect);
}

function check_valid_from_until_date($valid_from, $valid_until)
{
	$today = new DateTime();
	$success = true;
	$message = '';

	if ($valid_from && (substr($valid_from, 0, 10) != '0000-00-00')) {
		$valid_firstday = new DateTime($valid_from);
		if ($today < $valid_firstday) {
			$success = false;
			$message = GENERIC_LOGIN_ERROR;
		}
	}
	if ($valid_until && (substr($valid_until, 0, 10) != '0000-00-00')) {
		$valid_lastday = new DateTime($valid_until);
		if ($today > $valid_lastday) {
			$success = false;
			$message = GENERIC_LOGIN_ERROR;
		}
	}
	return array('success' => $success, 'message' => $message);
}

function sendlogindata($table, $ids)
{
	global $translate, $settings;

	foreach ($ids as $id) {
		$row = db_row('SELECT * FROM ' . $table . ' WHERE id = :id', array('id' => $id));

		$newpassword = createPassword();

		$mail = $translate['cms_sendlogin_mail'];
		$mail = str_ireplace('{sitename}',  $_SERVER['HTTP_HOST'] . $settings['rootdir'] , $mail);
		$mail = str_ireplace('{password}', $newpassword, $mail);
		$mail = str_ireplace('{orgname}', $_SESSION['organisation']['label'], $mail); 

		$result = sendmail($row['email'], $row['naam'], $translate['cms_sendlogin_mailsubject'], $mail);
		if ($result) {
			$message = $result;
			$success = false;
		} else {
			$success = true;
			db_query('UPDATE ' . $table . ' SET pass = :pass WHERE id = :id', array('pass' => md5($newpassword), 'id' => $id));
			$message = translate('cms_sendlogin_confirm');
		}
	}

	return array($success, $message);
}

function createPassword($length = 10, $possible = '23456789AaBbCcDdEeFfGgHhijJkKLMmNnoPpQqRrSsTtUuVvWwXxYyZz!$-_@#%^*()+=')
{
	$password = "";
	$i = 0;
	while ($i < $length) {
		$char = substr($possible, mt_rand(0, strlen($possible) - 1), 1);
		if (!strstr($password, $char)) {
			$password .= $char;
			$i++;
		}
	}
	return $password;
}

function loadSessionData($user) {
	$_SESSION['user'] = $user;
	$_SESSION['usergroup'] = db_row('
		SELECT ug.*, (SELECT level FROM cms_usergroups_levels AS ul WHERE ul.id = ug.userlevel) AS userlevel 
		FROM cms_usergroups AS ug 
		WHERE ug.id = :id AND (NOT ug.deleted OR ug.deleted IS NULL)', array('id' => $user['cms_usergroups_id']));
	$_SESSION['organisation'] = db_row('
		SELECT * 
		FROM organisations 
		WHERE id = :id AND (NOT organisations.deleted OR organisations.deleted IS NULL)', array('id' => $_SESSION['usergroup']['organisation_id']));
}

function loginasuser($table,$ids) {
	dump($ids[0]);
	$id = $ids[0];
	if($_SESSION['user2'] or !$_SESSION['user']['is_admin']) {
		$success = false;
		$message = '"Login als" is alleen voor admingebruikers';
	} else {
		$_SESSION['user2'] = $_SESSION['user'];
		$_SESSION['camp2'] = $_SESSION['camp'];
		$_SESSION['usergroup2'] = $_SESSION['usergroup'];
		$_SESSION['organisation2'] = $_SESSION['organisation'];
		$_SESSION['user'] = db_row('SELECT * FROM cms_users WHERE id=:id',array('id'=>$id));
		loadSessionData($_SESSION['user']);
		$camplist = camplist();
		$_SESSION['camp'] = reset($camplist);
		$success = true;
		$message = 'Logged in as '.$_SESSION['user']['naam'];
	}

	return array($success,$message,true);
}
