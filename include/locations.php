<?php

	$table = $action;
	$ajax = checkajax();

	if(!$ajax) {

		if(!$_SESSION['camp']['id']) trigger_error("The list of locations is not available when there is no camp selected");

		initlist();

		$cmsmain->assign('title','Locations');
		listsetting('search', array('sizes.label'));

		$data = getlistdata('SELECT *, (SELECT COUNT(id) FROM stock WHERE location_id = locations.id AND NOT deleted) AS boxcount FROM locations WHERE camp_id = '.$_SESSION['camp']['id']);

		addcolumn('text','Locations','label');
		addcolumn('text','Number of boxes','boxcount');

		listsetting('allowsort',false);
		listsetting('allowcopy',true);
		listsetting('add', 'Add a location');

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
