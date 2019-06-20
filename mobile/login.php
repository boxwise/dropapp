<?php

# TODO merge this with ajax/login.php

$_POST['pass'] = md5($_POST['pass']);

$row = db_row('SELECT *, "org" AS usertype FROM cms_users WHERE email != "" AND email = :email AND (NOT deleted OR deleted IS NULL)', array('email' => $_POST['email']));
if ($row) { #e-mailaddress exists in database
	if ($row['pass'] == $_POST['pass']) { # password is correct
		$success = true;
		$message = '';

		$today = new DateTime();
		if ($row['valid_firstday']) {
			$valid_firstday = new DateTime($row['valid_firstday']);
			if ($today < $valid_firstday) {
				$success = false;
				$message = "This user account is not yet valid";
			}
		}
		if ($row['valid_lastday']) {
			$valid_lastday = new DateTime($row['valid_lastday']);
			if ($today > $valid_lastday) {
				$success = false;
				$message = "This user account has expired";
			}
		}

		if ($success) {
			$_SESSION['user'] = $row;
			$_SESSION['usergroup'] = db_row('SELECT ug.*, (SELECT level FROM cms_usergroups_levels AS ul WHERE ul.id = ug.userlevel) AS userlevel FROM cms_usergroups AS ug WHERE ug.id = :id', array('id' => $_SESSION['user']['cms_usergroups_id']));
			$_SESSION['organisation'] = db_row('SELECT * FROM organisations WHERE id = :id', array('id' => $_SESSION['usergroup']['organisation_id']));

			db_query('UPDATE cms_users SET lastlogin = NOW(), lastaction = NOW() WHERE id = :id', array('id' => $_SESSION['user']['id']));
			logfile('Mobile user logged in with ' . $_SERVER['HTTP_USER_AGENT']);

			if (isset($_POST['autologin'])) {
				setcookie("autologin_user", $_POST['email'], time() + (3600 * 24 * 90), '/');
				setcookie("autologin_pass", $_POST['pass'], time() + (3600 * 24 * 90), '/');
			} else {
				setcookie("autologin_user", null, time() - 3600, '/');
				setcookie("autologin_pass", null, time() - 3600, '/');
			}
		}
	} else { # password is not correct
		$success = false;
		$message = translate('cms_login_error_wrongpassword');
		$redirect = false;
		logfile('Attempt to login with mobile and wrong passford for ' . $_POST['email']);
	}
} else { # user not found
	$success = false;
	$message = translate('cms_login_error_usernotfound');
	$redirect = false;
	logfile('Attempt to login with mobile and unknown user ' . $_POST['email']);
}

if ($success) {
	if ($_GET['barcode']) $uri = 'barcode=' . $_GET['barcode'];

	redirect('?' . $uri);
} else {
	redirect('?warning=true&message=' . $message);
}
