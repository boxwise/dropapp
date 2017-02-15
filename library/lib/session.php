<?

function checksession() {
	global $settings;
	
	if(isset($_SESSION['user'])) { # a valid session exists
		db_query('UPDATE '.$settings['cms_usertable'].' SET lastaction = NOW() WHERE id = :id', array('id'=>$_SESSION['user']['id']));			
	} else { # no valid session exists	
		if(isset($_COOKIE['autologin_user'])) { # a autologin cookie exists
			$user = db_row('SELECT * FROM '.$settings['cms_usertable'].' WHERE email != "" AND email = :email AND pass = :pass',array('email'=>$_COOKIE['autologin_user'], 'pass'=>$_COOKIE['autologin_pass']));
			if($user) {
				$_SESSION['user'] = $user;
				db_query('UPDATE '.$settings['cms_usertable'].' SET lastlogin = NOW(), lastaction = NOW() WHERE id = :id',array('id'=>$_SESSION['user']['id']));
			}
		} else {
			redirect($settings['rootdir'] . $settings['cmsdir'].'/login.php?destination='.urlencode($_SERVER['REQUEST_URI']));
		}
	}
}

function logout($redirect = false) {
	global $settings;
	
	db_query('UPDATE '.$settings['cms_usertable'].' SET lastaction = NULL WHERE id = :id', array('id'=>$_SESSION['user']['id']));
	
	setcookie("autologin_user", null, time()-3600, '/');
	setcookie("autologin_pass", null, time()-3600, '/');

	session_unset();
	session_destroy();
	
	if(!$redirect) $redirect = '?';
	redirect($redirect);
}
