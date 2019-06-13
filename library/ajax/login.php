<?php

	$_POST['pass'] = md5($_POST['pass']);

	$row = db_row('SELECT *, "org" AS usertype FROM cms_users WHERE email != "" AND email = :email AND NOT deleted',array('email'=>$_POST['email']));

	if($row) { #e-mailaddress exists in database
		if($row['pass']==$_POST['pass']) { # password is correct
			$success = true;
			$message = '';

			$_SESSION['user'] = $row;
			$_SESSION['usergroup'] = db_row('SELECT * FROM cms_usergroups WHERE id = :id',array('id'=>$_SESSION['user']['cms_usergroups_id']));
			$_SESSION['organisation'] = db_row('SELECT * FROM organisations WHERE id = :id',array('id'=>$_SESSION['usergroup']['organisation_id']));

			db_query('UPDATE cms_users SET lastlogin = NOW(), lastaction = NOW() WHERE id = :id',array('id'=>$_SESSION['user']['id']));
			logfile('User logged in with '.$_SERVER['HTTP_USER_AGENT']);

			if(isset($_POST['autologin'])) {
				setcookie("autologin_user", $_POST['email'], time()+(3600*24*90), '/');
				setcookie("autologin_pass", $_POST['pass'], time()+(3600*24*90), '/');
			} else {
				setcookie("autologin_user", null, time()-3600, '/');
				setcookie("autologin_pass", null, time()-3600, '/');
			}
			#include('update.php');
			$redirect = $settings['rootdir'].'/?action=start';
		} else { # password is not correct
			$success = false;
			$message = translate('cms_login_error_wrongpassword');
			$redirect = false;
			logfile('Attempt to login with wrong passford for '.$_POST['email']);
		}
	} else { # user not found
		$success = false;
		$message = translate('cms_login_error_usernotfound');
		$redirect = false;
		logfile('Attempt to login as unknown user '.$_POST['email']);
	}

	$return = array("success" => $success, 'message'=> $message, 'redirect'=> $redirect);

	echo json_encode($return);
