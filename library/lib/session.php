<?php

function checksession()
{
	global $settings;

	if (isset($_SESSION['user'])) { # a valid session exists

		db_query('UPDATE cms_users SET lastaction = NOW() WHERE id = :id', array('id' => $_SESSION['user']['id']));
		$_SESSION['user'] = db_row('SELECT * FROM cms_users WHERE id = :id', array('id' => $_SESSION['user']['id']));

		# Check if account is not expired
		$in_valid_dates = check_valid_from_until_date($row['valid_firstday'], $row['valid_lastday']);
		if (!$in_valid_dates['success']) {
			redirect($settings['rootdir'] . $settings['cmsdir'] . '/login.php?destination=' . urlencode($_SERVER['REQUEST_URI']) . '&warning=1&message=' . $in_valid_dates['message']);
		}
	} else { # no valid session exists
		if (isset($_COOKIE['autologin_user'])) { # a autologin cookie exists
			$user = db_row('SELECT * FROM cms_users WHERE email != "" AND email = :email AND pass = :pass', array('email' => $_COOKIE['autologin_user'], 'pass' => $_COOKIE['autologin_pass']));
			if ($user) {
				$_SESSION['user'] = $user;
				db_query('UPDATE cms_users SET lastlogin = NOW(), lastaction = NOW() WHERE id = :id', array('id' => $_SESSION['user']['id']));
			}
		} else {
			redirect($settings['rootdir'] . $settings['cmsdir'] . '/login.php?destination=' . urlencode($_SERVER['REQUEST_URI']));
		}
	}
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
