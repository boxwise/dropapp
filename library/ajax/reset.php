<?php

$row = db_row('SELECT *, "org" AS usertype FROM cms_users WHERE email != "" 
AND email = :email 
AND ((valid_firstday < NOW() AND valid_lastday > NOW()) OR valid_lastday = "0000-00-00")
AND NOT deleted ',array('email'=>$_POST['email']));

	if($row) { #e-mailaddress exists in database

		$hash = md5(uniqid(time()));

		db_query('UPDATE cms_users SET resetpassword = :hash WHERE id = :id ',array('hash'=>$hash,'id'=>$row['id']));

		$message = $translate['cms_reset_mail'];
		$message = str_ireplace('{sitename}',$translate['site_name'].' ('.$_SERVER['HTTP_HOST'].')',$message);

		if($row['usertype']=='family') {
			$message = str_ireplace('{link}','http://'.$_SERVER['HTTP_HOST'].$settings['cmsdir'].'/reset.php?peopleid='.$row['id'].'&hash='.$hash,$message);
		} else {
			$message = str_ireplace('{link}','http://'.$_SERVER['HTTP_HOST'].$settings['cmsdir'].'/reset.php?userid='.$row['id'].'&hash='.$hash,$message);
		}

		$result = sendmail($row['email'], $row['naam'], $translate['cms_reset_mailsubject'], $message);
		if($result) {
			$message = $result;
			$success = false;
		} else {
			$success = true;
			$message = "Within a few minutes you will receive an e-mail with further instructions to reset your password.";
		}

		logfile('Wachtwoord reset aangevraagd voor '.$_POST['email']);

	} else { # user not found
		$success = false;
		$message = GENERIC_LOGIN_ERROR;
		$redirect = '';
		logfile('Poging tot wachtwoord reset door onbekende gebruiker '.$_POST['email']);
	}


	$return = array("success" => $success, 'message'=> $message, 'redirect'=>false);

	echo json_encode($return);
