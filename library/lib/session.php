<?php

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
				// TODO check if usergroups and organisation needs to be loaded
				$_SESSION['user'] = $user;
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
			$message = "This user account is not yet valid.";
		}
	}
	if ($valid_until && (substr($valid_until, 0, 10) != '0000-00-00')) {
		$valid_lastday = new DateTime($valid_until);
		if ($today > $valid_lastday) {
			$success = false;
			$message = "This user account is expired.";
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
			$succes = false;
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
