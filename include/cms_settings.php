<?php

	$table = 'settings';
	$ajax = checkajax();
	if(!DEFINED('CORE')) include('core.php');

	if(!$ajax) {

		initlist();

		$cmsmain->assign('title',$translate['cms_settings']);

		$hasCategory = db_fieldexists($table,'category_id');
		$hasBilanguage = db_fieldexists($table,'description_nl');

		if($hasBilanguage) {
			listsetting('search', array('code','description_'.$lan));
		} else {
			listsetting('search', array('code','description'));
		}

		if($hasCategory) {
			$hasSeq = db_fieldexists('settings_categories','seq');
			listfilter(array('label'=>'Filter op categorie','query'=>'SELECT id AS value, name AS label FROM settings_categories '.(!$_SESSION['user']['is_admin']?' WHERE NOT admin_only':'').' '.($hasSeq?'ORDER BY seq':'ORDER BY id'),'filter'=>'category_id'));
			$data = getlistdata('SELECT t.* FROM '.$table.' AS t LEFT OUTER JOIN settings_categories AS c ON t.category_id = c.id '.(!$_SESSION['user']['is_admin']?' WHERE NOT t.hidden AND (c.admin_only IS NULL OR NOT c.admin_only)':''));

		} else {
			$data = getlistdata('SELECT t.* FROM '.$table.' AS t'.($_SESSION['user']['is_admin']?'':' WHERE NOT t.hidden'));
		}

		if($hasBilanguage) {
			addcolumn('text',$translate['cms_settings_description'],'description_'.$lan,array('width'=>'33%'));
		} else {
			addcolumn('text',$translate['cms_settings_description'],'description',array('width'=>'33%'));
		}
		addcolumn('text',$translate['cms_settings_value'],'value',array('width'=>'33%'));
		if($_SESSION['user']['is_admin']) addcolumn('text',$translate['cms_settings_code'],'code',array('width'=>'33%'));

		listsetting('add', $translate['cms_settings_new']);
		listsetting('allowdelete', $_SESSION['user']['is_admin']);
		listsetting('allowadd', $_SESSION['user']['is_admin']);
		listsetting('allowcopy', $_SESSION['user']['is_admin']);

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
		}

		$return = array("success" => $success, 'message'=> $message, 'redirect'=>$redirect);

		echo json_encode($return);
		die();
	}
