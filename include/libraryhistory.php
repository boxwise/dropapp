<?php

	$table = 'library_transactions';
	$ajax = checkajax();

	if(!$ajax) {

		initlist();

		$cmsmain->assign('title','History for '.db_value('SELECT CONCAT(booktitle_en,IF(booktitle_ar!="",CONCAT(" - ",booktitle_ar),""),IF(author!="",CONCAT(" (",author,")"),"")) FROM library WHERE id = :id',array('id'=>$_GET['id'])));

		$data = getlistdata('SELECT transaction_date AS dateout, 
(SELECT transaction_date FROM library_transactions AS t2 WHERE t.book_id = t2.book_id AND t2.transaction_date > t.transaction_date ORDER BY transaction_date LIMIT 1) AS datein, IF(t.people_id = -1,t.comment,CONCAT(firstname," ",lastname," (",container,")")) AS name FROM library_transactions AS t LEFT OUTER JOIN people AS p ON p.id = t.people_id WHERE book_id = '.intval($_GET['id']).' AND status = "out" ORDER BY transaction_date DESC');

		addcolumn('text','Date out','dateout');
		addcolumn('text','Date back','datein');
		
		addcolumn('text','Rented out to','name');
		
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
