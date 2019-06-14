<?php

	$table = $action;
	$ajax = checkajax();

	if(!$ajax) {

		initlist();

		$cmsmain->assign('title','User groups');
		listsetting('search', array('g.label'));

		$data = getlistdata('SELECT g.*, IFNULL(GROUP_CONCAT(c.name ORDER BY c.seq SEPARATOR ", "),"") AS camps, l.shortlabel AS userlevel FROM cms_usergroups AS g 
LEFT OUTER JOIN cms_usergroups_camps AS x ON x.cms_usergroups_id = g.id
LEFT OUTER JOIN cms_usergroups_levels AS l ON l.id = g.userlevel
LEFT OUTER JOIN camps AS c ON x.camp_id = c.id
WHERE (NOT c.deleted OR c.deleted IS NULL)
GROUP BY g.id');

		addcolumn('text','Name','label');
		addcolumn('text','Level','userlevel');
		addcolumn('text','Camps','camps');

		listsetting('allowsort',true);
		listsetting('add', 'Add a User Group');

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
