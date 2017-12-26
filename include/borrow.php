<?php

	$table = 'borrow_items';
	$ajax = checkajax();
	if(!DEFINED('CORE')) include('core.php');


	if(!$ajax) {

		initlist();

		$cmsmain->assign('title','Borrow items');

		$data = getlistdata('SELECT b.visible, b.visible AS editable, b.label, bc.label AS category, b.id,

	(SELECT IF(status="out",(SELECT CONCAT(firstname," ",lastname) FROM people WHERE id = people_id),"") FROM borrow_transactions AS t WHERE t.bicycle_id = b.id ORDER BY transaction_date DESC LIMIT 1) AS user, 
	(SELECT IF(status="out",transaction_date,0) FROM borrow_transactions AS t WHERE t.bicycle_id = b.id ORDER BY transaction_date DESC LIMIT 1) AS date
FROM borrow_items AS b LEFT OUTER JOIN borrow_categories AS bc ON bc.id = b.category_id WHERE NOT b.deleted');

		addcolumn('text','Category','category');
		addcolumn('text','Name','label');
		addcolumn('text','Rented out to','user');
		addcolumn('datetime','Date','date');

		addbutton('edititem','Edit item',array('icon'=>'fa-edit','oneitemonly'=>true));
		
		listsetting('allowsort', true);
		listsetting('allowdelete', false);
		listsetting('allowshowhide', true);
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
				$redirect = '?action=borrow_edititem&id='.$id;
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
