<?php
	$table = 'cms_users';

	if($_GET['return']) {
		db_query('INSERT INTO borrow_transactions (transaction_date, bicycle_id, people_id, status) VALUES (NOW(), :id, :people_id, "in")', array('id'=>$_GET['return'],'people_id'=>$_GET['user']));
		redirect('?action=borrow');
	}

	if($_POST) {
		
		$table = 'borrow_transactions';
		$keys = array('transaction_date','bicycle_id','people_id','status');

		$handler = new formHandler($table);
		$handler->savePost($keys);

		redirect('?action='.$_POST['_origin']);
	}



	// open the template

	$data = db_row('SELECT b.*, bt.transaction_date, bt.people_id, bt.status, CONCAT(p.firstname," ",p.lastname) AS user FROM borrow_items AS b LEFT OUTER JOIN borrow_transactions AS bt ON bt.bicycle_id = b.id LEFT OUTER JOIN people AS p ON bt.people_id = p.id WHERE b.id = :id ORDER BY transaction_date DESC ',array('id'=>$id));

	if($data['status']=='out') {

		$cmsmain->assign('title',$data['user'].' is returning '.$data['label']);
		$cmsmain->assign('data',$data);
		$cmsmain->assign('include','borrow_return.tpl');
		
	} else {
			
		$data = array();	
		$data['bicycle_id'] = $id;
		$data['label'] = db_value('SELECT label FROM borrow_items WHERE id = :id',array('id'=>$id));
		$data['status'] = 'out';
		$data['category_id'] = db_value('SELECT category_id FROM borrow_items WHERE id = :id',array('id'=>$id));
		$data['transaction_date'] = strftime("%Y-%m-%d %H:%M:%S");

		$cmsmain->assign('title','Start a new rental for '.$data['label']);
		
		addfield('hidden','','bicycle_id');
		addfield('hidden','','status');
		addfield('hidden','','transaction_date');
		
		$time = strftime('%H')+(strftime('%M')/60);
		
		if($data['category_id']==1) {
			if($time>16.5) {
				addfield('custom','&nbsp','<h2><span class="warning">After 16:30 we do not start new rentals!</span></h2>');
			}
			addfield('custom','&nbsp','<h3>Check the user\'s Bicycle Certificate, and make sure the user has a reflective vest, front light, helmet and key. <br />Asure the user to back before '.strftime("%H:%M",strtotime('+3 Hours')).'</h3>');
			addfield('select','Find person','people_id',array('required'=>true, 'multiple'=>false, 'query'=>'SELECT p.id AS value, CONCAT(p.firstname, " ", p.lastname, " (", p.container, ")") AS label, NOT visible AS disabled FROM people AS p WHERE p.bicycletraining AND NOT p.deleted AND camp_id = '.$_SESSION['camp']['id'].' GROUP BY p.id ORDER BY SUBSTRING(REPLACE(container,"PK","Z"),1,1), SUBSTRING(REPLACE(container,"PK","Z"), 2, 10)*1'));
		} else {
			addfield('custom','&nbsp','<h3>Take the resident\'s ID and make sure the resident is 16 or older<br />Asure the user to bring everything back before 16:30</h3>');
			addfield('select','Find person','people_id',array('required'=>true, 'multiple'=>false, 'query'=>'SELECT p.id AS value, CONCAT(p.firstname, " ", p.lastname, " (", p.container, ")") AS label, NOT visible AS disabled FROM people AS p WHERE DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 >= 16 AND NOT p.deleted AND camp_id = '.$_SESSION['camp']['id'].' GROUP BY p.id ORDER BY SUBSTRING(REPLACE(container,"PK","Z"),1,1), SUBSTRING(REPLACE(container,"PK","Z"), 2, 10)*1'));
		}

		addfield('line','','');


		$cmsmain->assign('include','cms_form.tpl');
		$cmsmain->assign('data',$data);
		$cmsmain->assign('formelements',$formdata);
		
	}

