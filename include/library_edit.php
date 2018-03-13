<?php

	if($_GET['return']) {
		db_query('INSERT INTO library_transactions (transaction_date, book_id, people_id, status) VALUES (NOW(), :id, :people_id, "in")', array('id'=>$_GET['return'],'people_id'=>$_GET['user']));
		redirect('?action=library');
	}

	if($_POST) {
		
		$table = 'library_transactions';
		$keys = array('transaction_date','book_id','people_id','status','comment');

		$handler = new formHandler($table);
		$handler->savePost($keys);

		redirect('?action='.$_POST['_origin']);
	}



	// open the template

	$data = db_row('SELECT b.*, TIME_TO_SEC(TIMEDIFF(NOW(),transaction_date))>610000 AS toolate, CONCAT(HOUR(TIMEDIFF(NOW(),transaction_date)),":",LPAD(MINUTE(TIMEDIFF(NOW(),transaction_date)),2,"0")) AS duration, bt.transaction_date, bt.people_id, bt.status, bt.comment AS btcomment, CONCAT(firstname," ",lastname," (",container,")") AS user FROM library AS b LEFT OUTER JOIN library_transactions AS bt ON bt.book_id = b.id LEFT OUTER JOIN people AS p ON bt.people_id = p.id WHERE b.id = :id ORDER BY transaction_date DESC ',array('id'=>$id));

	if($data['status']=='out') {

		if($data['people_id']==-1) $data['user'] = $data['btcomment'];
		$cmsmain->assign('title',$data['user'].' is returning '.$data['code'].($data['booktitle_en']?' - '.$data['booktitle_en']:""));
		$cmsmain->assign('data',$data);
		$cmsmain->assign('include','library_return.tpl');
		
	} else {
		$data['visible'] = 1;
		$data['status'] = "out";
		$data['transaction_date'] = strftime("%Y-%m-%d %H:%M:%S");

		$translate['cms_form_submit'] = 'Start borrowing';
		$cmsmain->assign('translate',$translate);
			
		$cmsmain->assign('title','Borrow out a book');

		addfield('select','Find book','book_id',array('required'=>true, 'multiple'=>false, 'query'=>'SELECT id AS value, CONCAT(code," - ",booktitle_en,IF(booktitle_ar!="",CONCAT(" - ",booktitle_ar),""),IF(author!="",CONCAT(" (",author,")"),"")) AS label FROM library WHERE visible
		'));

		$people = db_array('SELECT p.id AS value, CONCAT(p.firstname, " ", p.lastname, " (", p.container, ")") AS label, NOT visible AS disabled FROM people AS p WHERE DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 >= 16 AND NOT p.deleted AND camp_id = '.$_SESSION['camp']['id'].' GROUP BY p.id ORDER BY SUBSTRING(REPLACE(container,"PK","Z"),1,1), SUBSTRING(REPLACE(container,"PK","Z"), 2, 10)*1');
		array_unshift($people, array('value'=>-1,'label'=>'Somebody else','disabled'=>0));

		addfield('select','Find person','people_id',array('required'=>true, 'multiple'=>false, 'options'=>$people, 'onchange'=>'toggleLibraryComment()'));
		addfield('text','Name of person','comment');
		
		addfield('hidden','','bicycle_id');
		addfield('hidden','','status');
		addfield('hidden','','transaction_date');
		

		addfield('line','','');


		$cmsmain->assign('include','cms_form.tpl');
		$cmsmain->assign('data',$data);
		$cmsmain->assign('formelements',$formdata);
		
	}

