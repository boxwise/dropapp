<?php

	$table = 'borrow_transactions';
	$ajax = checkajax();
	if(!DEFINED('CORE')) include('core.php');


	if(!$ajax) {

		initlist();

		$cmsmain->assign('title','History for '.db_value('SELECT label FROM borrow_items WHERE id = :id',array('id'=>$_GET['id'])));

		$data = getlistdata('SELECT transaction_date AS dateout, (SELECT location FROM borrow_locations WHERE id = location_id) AS location,
(SELECT transaction_date FROM borrow_transactions AS t2 WHERE t.bicycle_id = t2.bicycle_id AND t2.transaction_date > t.transaction_date ORDER BY transaction_date LIMIT 1) AS datein, 
(SELECT l.location FROM borrow_transactions AS t2 LEFT OUTER JOIN borrow_locations AS l ON l.id = t2.location_id WHERE t.bicycle_id = t2.bicycle_id AND t2.transaction_date > t.transaction_date ORDER BY transaction_date LIMIT 1) AS returnloc, 

CONCAT(p.firstname," ",p.lastname) AS name FROM borrow_transactions AS t LEFT OUTER JOIN people AS p ON p.id = t.people_id WHERE bicycle_id = '.intval($_GET['id']).' AND status = "out" ORDER BY transaction_date DESC');

		addcolumn('text','Date out','dateout');
		addcolumn('text','Date back','datein');
		addcolumn('text','Rented out to','name');
		addcolumn('text','Picked up at','location');
		addcolumn('text','Return at','returnloc');
		
		listsetting('allowedit', false);
		listsetting('allowsort', true);
		listsetting('allowdelete', false);
		listsetting('allowshowhide', false);
		listsetting('allowadd', false);
		listsetting('allowselect', false);
		listsetting('allowselectall', false);

		$cmsmain->assign('data',$data);
		$cmsmain->assign('listconfig',$listconfig);
		$cmsmain->assign('listdata',$listdata);
		$cmsmain->assign('include','cms_list.tpl');

	} else {
		switch ($_POST['do']) {
		}

		$return = array("success" => $success, 'message'=> $message, 'redirect'=>$redirect);

		echo json_encode($return);
		die();
	}
