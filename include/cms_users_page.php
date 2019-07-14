<?php

	if($_SESSION['user']['is_admin'] || $_SESSION['usergroup']['userlevel'] > db_value('SELECT MIN(level) FROM cms_usergroups_levels')){

	if(!$ajax) {

		initlist();

		$cmsmain->assign('title',$translate['cms_users']);
		
		$camps = db_value('SELECT GROUP_CONCAT(id) FROM cms_usergroups_camps AS uc, camps AS c WHERE (NOT c.deleted OR c.deleted IS NULL) AND uc.camp_id = c.id AND uc.cms_usergroups_id = :usergroup', array('usergroup'=>$_SESSION['usergroup']['id']));
		
		$data = getlistdata($cms_users_admin_query);
		if (!$_SESSION['user']['is_admin']){
			$data = array_merge($data, db_array($cms_users_nonadmin_query));
		}

		addcolumn('text',$translate['cms_users_naam'],'naam');
		addcolumn('text',$translate['cms_users_email'],'email');
		addcolumn('text','Role','usergroup');
		addcolumn('date','Valid from','valid_firstday');
		addcolumn('date','Valid until','valid_lastday');

		listsetting('add', $translate['cms_users_new']);
		listsetting('width', 12);
		listsetting('allowsort', true);

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
				if($success) {
					foreach ($ids as $id) {
						db_query('UPDATE cms_users SET email = CONCAT(email,".deleted.",id) WHERE id = :id', array('id'=>$id));
					}
				}
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
	
	} else {
		trigger_error('You do not have access to this menu. Please ask your admin to change this!');
	}

	function loginasuser($table,$ids) {

		$id = $ids[0];
		if($_SESSION['user2'] or !$_SESSION['user']['is_admin']) {
			$success = false;
			$message = '"Login als" is alleen voor admingebruikers';
		} else {
			$_SESSION['user2'] = $_SESSION['user'];
			$_SESSION['camp2'] = $_SESSION['camp'];
			$_SESSION['usergroup2'] = $_SESSION['usergroup'];
			$_SESSION['organisation2'] = $_SESSION['organisation'];
			$_SESSION['user'] = db_row('SELECT * FROM cms_users WHERE id=:id',array('id'=>$id));
			$_SESSION['usergroup'] = db_row('SELECT ug.*, (SELECT level FROM cms_usergroups_levels AS ul WHERE ul.id = ug.userlevel) AS userlevel FROM cms_usergroups AS ug WHERE ug.id = :id AND (NOT ug.deleted OR ug.deleted IS NULL)',array('id'=>$_SESSION['user']['cms_usergroups_id']));
			$_SESSION['organisation'] = db_row('SELECT * FROM organisations AS o WHERE id = :id AND (NOT o.deleted OR o.deleted IS NULL)',array('id'=>$_SESSION['usergroup']['organisation_id']));
			$camplist = camplist();
			$_SESSION['camp'] = $camplist[0];
			$success = true;
			$message = 'Nu ingelogd als '.$_SESSION['user']['naam'];
		}

		return array($success,$message,true);
	}
