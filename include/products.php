<?php

	$table = $action;
	$ajax = checkajax();
	if(!DEFINED('CORE')) include('core.php');

	if(!$ajax) {

		initlist();

		$cmsmain->assign('title','Products');
		listsetting('search', array('name', 'g.label','products.comments'));
		
		$locations = join(',',db_simplearray('SELECT id, id FROM locations WHERE visible AND camp_id = :camp_id',array('camp_id'=>$_SESSION['camp']['id'])));

		$data = getlistdata('SELECT products.*, sg.label AS sizegroup, g.label AS gender, CONCAT(products.value," drops") AS drops, COALESCE(SUM(s.items),0) AS items, IF(SUM(s.items),1,0) AS preventdelete FROM '.$table.'
			LEFT OUTER JOIN genders AS g ON g.id = products.gender_id
			LEFT OUTER JOIN sizegroup AS sg ON sg.id = products.sizegroup_id
			LEFT OUTER JOIN stock AS s ON s.product_id = products.id AND NOT s.deleted AND s.location_id IN ('.$locations.') 
			WHERE NOT products.deleted AND camp_id = '.$_SESSION['camp']['id'].'
			GROUP BY products.id
		');


		addcolumn('text','Product name','name');
		addcolumn('text','Gender','gender');
		addcolumn('text','Sizegroup','sizegroup');
		addcolumn('text','Items','items');
		if($_SESSION['camp']['market']) addcolumn('text','Price','drops');
		addcolumn('text','Comments','comments');
		if($_SESSION['camp']['id']==1) addcolumn('toggle','In container','stockincontainer',array('do'=>'togglecontainer'));

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
		}

		$return = array("success" => $success, 'message'=> $message, 'redirect'=>$redirect, 'newvalue'=>$newvalue);

		echo json_encode($return);
		die();
	}
