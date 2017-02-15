<?php

	$_POST['pass'] = md5($_POST['pass']);

	$row = db_row('SELECT *, "org" AS usertype FROM '.$settings['cms_usertable'].' WHERE email != "" AND email = :email AND NOT deleted',array('email'=>$_POST['email']));

	if($row) { #e-mailaddress exists in database
		if($settings['local_adminonly'] && !$row['is_admin'] && $_SERVER['Local']) {
			$success = false;
			$message = translate('cms_login_error_adminonly').$settings['local_adminonly'];
			$redirect = false;
			logfile('Login on the local-website is blocked for '.$_POST['email']);
		} elseif($row['pass']==$_POST['pass']) { # password is correct
			$success = true;
			$message = '';

			if($_POST['destination']) {
				$redirect = $_POST['destination'];
			} else {
				$firstpage = db_value('SELECT f.include FROM cms_functions AS f, cms_functions AS f2, cms_access AS a WHERE f.parent_id = f2.id AND a.cms_functions_id = f.id AND (a.cms_users_id = :id OR '.intval($row['is_admin']).'=1) GROUP BY f.id ORDER BY f2.seq, f.seq LIMIT 1',array('id'=>$row['id']));
				$redirect = $settings['rootdir'].'/?action='.$firstpage;
			}

			$_SESSION['user'] = $row;

			db_query('UPDATE '.$settings['cms_usertable'].' SET lastlogin = NOW(), lastaction = NOW() WHERE id = :id',array('id'=>$_SESSION['user']['id']));
			logfile('User logged in with '.$_SERVER['HTTP_USER_AGENT']);

			if(isset($_POST['autologin'])) {
				setcookie("autologin_user", $_POST['email'], time()+(3600*24*90), '/');
				setcookie("autologin_pass", $_POST['pass'], time()+(3600*24*90), '/');
			} else {
				setcookie("autologin_user", null, time()-3600, '/');
				setcookie("autologin_pass", null, time()-3600, '/');
			}
			#include('update.php');
			$redirect = '/?action=start';
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
