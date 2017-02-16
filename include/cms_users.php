<?php

	$table = $action;
	$ajax = checkajax();
	if(!DEFINED('CORE')) include('core.php');


	if(!$ajax) {

		initlist();

		$cmsmain->assign('title',$translate['cms_users']);

		$data = getlistdata('SELECT cms_users.*, NOT is_admin AS visible, IFNULL(GROUP_CONCAT(c.name ORDER BY c.seq SEPARATOR ", "),IF(cms_users.is_admin,"All camps","")) AS camps FROM cms_users LEFT OUTER JOIN cms_users_camps AS x ON x.cms_users_id = cms_users.id LEFT OUTER JOIN camps AS c ON c.id = x.camps_id '.($_SESSION['user']['is_admin']?'':' WHERE NOT is_admin').' GROUP BY cms_users.id');

		addcolumn('text',$translate['cms_users_naam'],'naam');
		addcolumn('text',$translate['cms_users_email'],'email');
		addcolumn('text','Camps','camps');
		addcolumn('datetime',$translate['cms_users_lastlogin'],'lastlogin');

		listsetting('add', $translate['cms_users_new']);
		listsetting('width', 9);

		addbutton('sendlogindata',$translate['cms_users_sendlogin'],array('icon'=>'fa-user','confirm'=>true));
		if($_SESSION['user']['is_admin'] && !$_SESSION['user2']) {
			addbutton('loginasuser',$translate['cms_users_loginas'],array('icon'=>'fa-users','confirm'=>true,'oneitemonly'=>true));
		}

		$cmsmain->assign('data',$data);
		$cmsmain->assign('listconfig',$listconfig);
		$cmsmain->assign('listdata',$listdata);
		$cmsmain->assign('include','cms_list.tpl');


	} else {
		switch ($_POST['do']) {
		    case 'move':
				$ids = json_decode($_POST['ids']);
		    	list($success, $message, $redirect) = listMove($table, $ids);
		        break;

		    case 'delete':
				$ids = explode(',',$_POST['ids']);
		    	list($success, $message, $redirect) = listDelete($table, $ids);
		        break;

		    case 'copy':
				$ids = explode(',',$_POST['ids']);
		    	list($success, $message, $redirect) = listCopy($table, $ids, 'code');
		        break;

		    case 'hide':
				$ids = explode(',',$_POST['ids']);
		    	list($success, $message, $redirect) = listShowHide($table, $ids, 0);
		        break;

		    case 'show':
				$ids = explode(',',$_POST['ids']);
		    	list($success, $message, $redirect) = listShowHide($table, $ids, 1);
		        break;

		    case 'sendlogindata':
				$ids = explode(',',$_POST['ids']);
		    	list($success, $message, $redirect) = sendlogindata($table, $ids);
		        break;

		    case 'loginasuser':
				$ids = explode(',',$_POST['ids']);
		    	list($success, $message, $redirect) = loginasuser($table,$ids);
		    	break;
		}

		$return = array("success" => $success, 'message'=> $message, 'redirect'=>$redirect);

		echo json_encode($return);
		die();
	}

	function loginasuser($table,$ids) {

		$id = $ids[0];
		if($_SESSION['user2'] or !$_SESSION['user']['is_admin']) {
			$success = false;
			$message = '"Login als" is alleen voor admingebruikers';
		} else {
			$_SESSION['user2'] = $_SESSION['user'];
			$_SESSION['user'] = db_row('SELECT * FROM cms_users WHERE id=:id',array('id'=>$id));
			$success = true;
			$message = 'Nu ingelogd als '.$_SESSION['user']['naam'];
		}

		return array($success,$message,true);
	}

	function sendlogindata($table, $ids) {
		global $translate, $settings;

        foreach ($ids as $id) {
			$row = db_row('SELECT * FROM '.$table.' WHERE id = :id',array('id'=>$id));

			$newpassword = createPassword();

			$mail = $translate['cms_sendlogin_mail'];
			$mail = str_ireplace('{sitename}',$settings['site_name'].' ('.$_SERVER['HTTP_HOST'].$settings['rootdir'].')',$mail);
			$mail = str_ireplace('{password}',$newpassword,$mail);

			$result = sendmail($row['email'], $row['naam'], $translate['cms_sendlogin_mailsubject'], $mail);
			if($result) {
				$message = $result;
				$succes = false;
			} else {
				$success = true;
				db_query('UPDATE '.$table.' SET pass = :pass WHERE id = :id',array('pass'=>md5($newpassword),'id'=>$id));
				$message = translate('cms_sendlogin_confirm');
			}
		}

		return array($success,$message);
	}

	function createPassword($length = 10, $possible = '23456789AaBbCcDdEeFfGgHhijJkKLMmNnoPpQqRrSsTtUuVvWwXxYyZz!$-_@#%^*()+=') {
		$password = "";
	 	$i = 0;
		while ($i < $length) {
			$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
			if (!strstr($password, $char)) {
				$password .= $char;
				$i++;
			}
		}
		return $password;
	}
