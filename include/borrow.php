<?php

	$table = 'borrow_items';
	$ajax = checkajax();
	if(!DEFINED('CORE')) include('core.php');


	if(!$ajax) {

		initlist();

		$cmsmain->assign('title','Borrow items');

		$data = getlistdata('SELECT b.visible, b.visible AS editable, b.label, bc.label AS category, b.id,

	(SELECT IF(status="out",CONCAT((SELECT CONCAT(firstname," ",lastname) FROM people WHERE id = people_id),IF(t.lights," ***","")),b.comment) FROM borrow_transactions AS t WHERE t.bicycle_id = b.id ORDER BY transaction_date DESC LIMIT 1) AS user, 
	(SELECT IF(status="out",
	
	IF(TIME_TO_SEC(TIMEDIFF(NOW(),transaction_date))>10400,CONCAT("<b class=\"warning\">",DATE_FORMAT(TIMEDIFF(NOW(),transaction_date),"%H:%i")," <i class=\"fa fa-warning\"></i></b>"),DATE_FORMAT(TIMEDIFF(NOW(),transaction_date),"%H:%i"))
	
	
	,"") FROM borrow_transactions AS t WHERE t.bicycle_id = b.id ORDER BY transaction_date DESC LIMIT 1) AS date
FROM borrow_items AS b LEFT OUTER JOIN borrow_categories AS bc ON bc.id = b.category_id WHERE NOT b.deleted');

		foreach($data as $key=>$value) {
				if(strpos($data[$key]['user'],"***")) $data[$key]['user'] = str_replace('***','💡',$data[$key]['user']);
		}
		addcolumn('text','Category','category');
		addcolumn('text','Name','label');
		addcolumn('text','Rented out to','user');
		addcolumn('html','Duration','date');

		addbutton('edititem','Edit item',array('icon'=>'fa-edit','oneitemonly'=>true));
		addbutton('borrowhistory','View history',array('icon'=>'fa-history','oneitemonly'=>true));
		
		listsetting('allowsort', true);
		listsetting('allowdelete', false);
		listsetting('allowshowhide', false);
		listsetting('allowadd', false);
		listsetting('allowselect', true);
		listsetting('allowselectall', false);

		$cmsmain->assign('data',$data);
		$cmsmain->assign('listconfig',$listconfig);
		$cmsmain->assign('listdata',$listdata);
		$cmsmain->assign('include','cms_list.tpl');

	} else {
		switch ($_POST['do']) {
			case 'edititem':
				$id = intval($_POST['ids']);
				$success = true;
				$redirect = '?action=borrowedititem&id='.$id;
				break;
			case 'borrowhistory':
				$id = intval($_POST['ids']);
				$success = true;
				$redirect = '?action=borrowhistory&id='.$id;
				break;
		    case 'move':
				$ids = json_decode($_POST['ids']);
		    	list($success, $message, $redirect) = listMove($table, $ids);
		        break;

		    case 'delete':
				$ids = explode(',',$_POST['ids']);
				foreach($ids as $id) {
					if($id) db_query('DELETE FROM borrow_transactions WHERE id = :id',array('id'=>$id));
				}
				$message = 'Transactions cancelled';
				$success = true;
				$redirect = true;
		        break;

		    case 'copy':
				$ids = explode(',',$_POST['ids']);
		    	list($success, $message, $redirect) = listCopy($table, $ids, 'menutitle');
		        break;

		    case 'hide':
				$ids = explode(',',$_POST['ids']);
		    	list($success, $message, $redirect) = listShowHide($table, $ids, 0);
		    	$message = $_POST['ids'];
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
