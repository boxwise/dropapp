<?php

	$table = $action;
	$ajax = checkajax();
	if(!DEFINED('CORE')) include('core.php');

	if(!$ajax) {

		initlist();

		$cmsmain->assign('title','Library');
		listsetting('search', array('code', 'booktitle_en', 'booktitle_ar', 'author', 'publisher'));
		
//  		listfilter(array('label'=>'By category','query'=>'SELECT id, label FROM product_categories ORDER BY seq','filter'=>'products.category_id'));

		$data = getlistdata('SELECT * FROM library');
		
		addcolumn('text','Book title','booktitle_en');
		addcolumn('html','Book title','booktitle_ar');
		addcolumn('text','Author','author');

// 		if($_SESSION['user']['is_admin'] || $_SESSION['user']['coordinator']) addbutton('export','Export',array('icon'=>'fa-file-excel-o','showalways'=>true));

		listsetting('allowsort',true);
		listsetting('allowcopy',false);
		listsetting('allowshowhide',false);
		listsetting('add', 'Add a product');
		listsetting('delete', 'Delete');

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

		    case 'togglecontainer':
		    	list($success, $message, $redirect, $newvalue) = listSwitch($table, 'stockincontainer', $_POST['id']);
		        break;
		    case 'export':
				$success = true;
		    	$redirect = '?action=products_export';
		        break;
		}

		$return = array("success" => $success, 'message'=> $message, 'redirect'=>$redirect, 'newvalue'=>$newvalue);

		echo json_encode($return);
		die();
	}
