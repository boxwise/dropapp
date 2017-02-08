<?

	$_POST['pass'] = md5($_POST['pass']);
	
	$row = db_row('SELECT *, "org" AS usertype FROM '.$settings['cms_usertable'].' WHERE email != "" AND email = :email AND NOT deleted',array('email'=>$_POST['email']));

	if($row) { #e-mailaddress exists in database
		if($settings['local_adminonly'] && !$row['is_admin'] && $_SERVER['Local']) {
			$success = false;
			$message = translate('cms_login_error_adminonly').$settings['local_adminonly'];					
			$redirect = false;
			logfile('Mobile login on the local-website is blocked for '.$_POST['email']);
		} elseif($row['pass']==$_POST['pass']) { # password is correct
			$success = true;
			$message = '';
			
			if($_POST['destination']) {
				$redirect = $_POST['destination'];
			}
			
			$_SESSION['user'] = $row;
			
			db_query('UPDATE '.$settings['cms_usertable'].' SET lastlogin = NOW(), lastaction = NOW() WHERE id = :id',array('id'=>$_SESSION['user']['id']));
			logfile('Mobile user logged in with '.$_SERVER['HTTP_USER_AGENT']);
			
			if(isset($_POST['autologin'])) {
				setcookie("autologin_user", $_POST['email'], time()+(3600*24*90), '/');
				setcookie("autologin_pass", $_POST['pass'], time()+(3600*24*90), '/');
			} else {
				setcookie("autologin_user", null, time()-3600, '/');
				setcookie("autologin_pass", null, time()-3600, '/');
			}
						
		} else { # password is not correct
			$success = false;
			$message = translate('cms_login_error_wrongpassword');					
			$redirect = false;
			logfile('Attempt to login with mobile and wrong passford for '.$_POST['email']);
		}
	} else { # user not found
		$success = false;
		$message = translate('cms_login_error_usernotfound');		
		$redirect = false;
		logfile('Attempt to login with mobile and unknown user '.$_POST['email']);
	}

	if($success) {
		if($redirect) redirect($redirect);
	} else {
		echo($message);
		var_export($_SERVER);
	}
	
	die('x');