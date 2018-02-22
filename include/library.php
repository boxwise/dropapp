<?php

	$table = 'library_transactions';
	$ajax = checkajax();
	if(!DEFINED('CORE')) include('core.php');


	if(!$ajax) {

		initlist();

		$cmsmain->assign('title','Library');

		$data = getlistdata('
		SELECT
			l.id, 
			CONCAT(booktitle_en,IF(booktitle_ar!="",CONCAT(" - ",booktitle_ar),""),IF(author!="",CONCAT(" (",author,")"),"")) AS title, 
			(SELECT CONCAT(firstname," ",lastname," (",container,")") FROM library_transactions AS lt, people AS p WHERE lt.people_id = p.id AND lt.book_id = l.id ORDER BY lt.transaction_date DESC LIMIT 1) AS name,
			(SELECT CONCAT(HOUR(TIMEDIFF(NOW(),transaction_date)),":",LPAD(MINUTE(TIMEDIFF(NOW(),transaction_date)),2,"0")) FROM library_transactions AS lt WHERE lt.book_id = l.id ORDER BY lt.transaction_date DESC LIMIT 1) AS duration
		
		FROM library AS l WHERE 
			(SELECT status FROM library_transactions AS lt WHERE lt.book_id = l.id ORDER BY lt.transaction_date DESC LIMIT 1) = "out"');

		addcolumn('text','Book','title');
		addcolumn('html','Rented out to','name');
		addcolumn('html','Duration','duration');

		
		listsetting('allowsort', true);
		listsetting('allowdelete', false);
		listsetting('allowshowhide', false);
		listsetting('allowadd', $_SESSION['user']['coordinator']||$_SESSION['user']['is_admin']);
		listsetting('allowselect', true);
		listsetting('allowselectall', false);
		
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
