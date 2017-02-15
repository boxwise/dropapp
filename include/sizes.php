<?php

	$table = $action;
	$ajax = checkajax();
	if(!DEFINED('CORE')) include('core.php');


	if(!$ajax) {

		$result = db_query('SELECT s.* FROM sizes AS s, sizegroup AS sg WHERE s.sizegroup_id = sg.id ORDER BY sg.seq, s.seq');
		$i=0;
		while($row = db_fetch($result)) {
			if($row['sizegroup_id']!=$oldgroup) $i+=10; else $i++;

			db_query('UPDATE sizes SET seq = :seq WHERE id = :id',array('seq'=>$i,'id'=>$row['id']));

			$oldgroup = $row['sizegroup_id'];
		}


		initlist();

		$cmsmain->assign('title','Sizes');
		listsetting('search', array('sizes.label'));

		$data = getlistdata('SELECT sizes.id, sizes.label, g.label AS sizegroup, CONCAT(sizes.portion,"%") AS portion FROM sizes LEFT OUTER JOIN sizegroup AS g ON g.id = sizes.sizegroup_id');

		addcolumn('text','Sizes','label');
		addcolumn('text','Size group','sizegroup');
		addcolumn('text','Portion','portion');

		listsetting('allowsort',false);
		listsetting('allowcopy',true);
		listsetting('add', 'Add a size');

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
