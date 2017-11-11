<?php

	$table = $action;
	$ajax = checkajax();
	if(!DEFINED('CORE')) include('core.php');


	if(!$ajax) {

		initlist();

		$cmsmain->assign('title','Bicycles');

		$data = getlistdata('SELECT * FROM bicycles ORDER BY label');

		addcolumn('text','Name','label');

		listsetting('add', $translate['cms_users_new']);
		listsetting('width', 9);
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
		        break;

		    case 'copy':
				$ids = explode(',',$_POST['ids']);
		    	list($success, $message, $redirect) = listCopy($table, $ids, 'menutitle');

		        break;

		    case 'hide':
				$ids = explode(',',$_POST['ids']);
		    	list($success, $message, $redirect) = listShowHide($table, $ids, 0);
		        break;

		    case 'show':
				$ids = explode(',',$_POST['ids']);
		    	list($success, $message, $redirect) = listShowHide($table, $ids, 1);
		        break;
		}

		$return = array("success" => $success, 'message'=> $message, 'redirect'=>$redirect);

		echo json_encode($return);
		die();
	}
