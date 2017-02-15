<?php
	$row = db_row('SELECT * FROM '.$settings['cms_usertable'].' WHERE resetpassword != "" AND resetpassword = :hash AND id = :userid AND NOT deleted',array('hash'=>$_POST['hash'], 'userid'=>$_POST['userid']));
	if(!$row) {
		$settings['cms_usertable'] = 'people';
		$row = db_row('SELECT *, "family" AS usertype, CONCAT(firstname," ",lastname) AS naam  FROM people WHERE resetpassword != "" AND resetpassword = :hash AND id = :userid AND NOT deleted',array('hash'=>$_POST['hash'], 'userid'=>$_POST['peopleid']));
		$_POST['userid'] = $_POST['peopleid'];
	}

	if($row) { #e-mailaddress exists in database

		if($_POST['pass']!=$_POST['pass2']) {
			$success = false;
			$message = translate('cms_reset_notmatching');
		} elseif(strlen($_POST['pass'])<8) {
			$success = false;
			$message = translate('cms_reset_tooshort');
		} else {

			db_query('UPDATE '.$settings['cms_usertable'].' SET pass = :pass, resetpassword = "" WHERE id = :userid',array('pass'=>md5($_POST['pass']),'userid'=>$_POST['userid']));
			$success = true;
			$message = translate('cms_reset_success');
		}

	} else { # user not found
		$success = false;
		$message = translate('cms_login_error_usernotfound');
		logfile('Poging tot invoer nieuw wachtwoord '.join(' ',$_POST));
	}

	$return = array("success" => $success, 'message'=> $message, 'redirect'=>'login.php');

	echo json_encode($return);
