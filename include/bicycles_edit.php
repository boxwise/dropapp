<?php
	$table = 'cms_users';

	if($_GET['return']) {
		db_query('INSERT INTO bicycle_transactions (transaction_date, bicycle_id, people_id, status) VALUES (NOW(), :id, :people_id, "in")', array('id'=>$_GET['return'],'people_id'=>$_GET['user']));
		redirect('?action=bicycles');
	}

	if($_POST) {
		
		$table = 'bicycle_transactions';
		$keys = array('transaction_date','bicycle_id','people_id','status');

		$handler = new formHandler($table);
		$handler->savePost($keys);

		redirect('?action='.$_POST['_origin']);
	}



	// open the template

	$data = db_row('SELECT b.*, bt.transaction_date, bt.people_id, bt.status, CONCAT(p.firstname," ",p.lastname) AS user FROM bicycles AS b LEFT OUTER JOIN bicycle_transactions AS bt ON bt.bicycle_id = b.id LEFT OUTER JOIN people AS p ON bt.people_id = p.id WHERE b.id = :id ORDER BY transaction_date DESC ',array('id'=>$id));

	if($data['status']=='out') {

		$cmsmain->assign('title',$data['user'].' is returning '.$data['label']);
		$cmsmain->assign('data',$data);
		$cmsmain->assign('include','bicycle_return.tpl');
		
	} else {
			
		$data = array();	
		$data['bicycle_id'] = $id;
		$data['label'] = db_value('SELECT label FROM bicycles WHERE id = :id',array('id'=>$id));
		$data['status'] = 'out';
		$data['transaction_date'] = strftime("%Y-%m-%d %H:%M:%S");

		$cmsmain->assign('title','Start a new bicycle rental for '.$data['label']);
		
		addfield('hidden','','bicycle_id');
		addfield('hidden','','status');
		addfield('hidden','','transaction_date');
		
		$time = strftime('%H')+(strftime('%M')/60);
		
		if($time>16.5) {
			addfield('custom','&nbsp','<h2><span class="warning">After 16:30 we do not start new rentals!</span></h2>');
		}
		addfield('custom','&nbsp','<h3>Check the user\'s Bicycle Certificate, and make sure the user has a reflective vest, front light, helmet and key. <br />Asure the user to back before '.strftime("%H:%M",strtotime('+3 Hours')).'</h3>');

		addfield('select','Find person','people_id',array('required'=>true, 'multiple'=>false, 'query'=>'SELECT p.id AS value, CONCAT(p.firstname, " ", p.lastname, " (", p.container, ")") AS label, NOT visible AS disabled FROM people AS p WHERE p.bicycletraining AND NOT p.deleted AND camp_id = '.$_SESSION['camp']['id'].' GROUP BY p.id ORDER BY p.lastname'));

		addfield('line','','');

/*
		if(db_value('SELECT id FROM bicycle_transactions WHERE bicycle_id ='.$id)) {	
			addfield('list','Bicycles','bicycles', array('width'=>10,'query'=>'
				SELECT DATE_FORMAT(transaction_date,"%d-%m-%Y %H:%i") AS transaction_date, CONCAT(p.firstname," ",p.lastname) AS name, status FROM bicycle_transactions AS bt LEFT OUTER JOIN people AS p ON p.id = bt.people_id WHERE bicycle_id = '.$id.' ORDER BY transaction_date DESC LIMIT 10', 
				'columns'=>array('label'=>'Bicycle', 'status'=>'in/out', 'transaction_date'=>'Date'),
				'allowedit'=>false,'allowadd'=>false,'allowsort'=>false,'allowselect'=>false,'allowselectall'=>false,'redirect'=>false,'modal'=>false));
		}			
*/


		$cmsmain->assign('include','cms_form.tpl');
		$cmsmain->assign('data',$data);
		$cmsmain->assign('formelements',$formdata);
		
	}

