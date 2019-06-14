<?php
	
	$table = 'cms_users';

	if(strftime('%w')==6) {
		$endtime = $_SESSION['camp']['bicycle_closingtime_saturday'];	
	} else {
		$endtime = $_SESSION['camp']['bicycle_closingtime'];	
	}
	if($endtime){
		$endtime = substr($endtime,0,strpos($endtime,':'))+(substr($endtime,strpos($endtime,':')+1)/60);
	} else {
		$endtime = 24;
	}
	
	if($_GET['return']) {
		verify_campaccess_people(intval($_GET['user']));
		verify_deletedrecord('people',intval($_GET['user']));
		
		db_query('INSERT INTO borrow_transactions (transaction_date, bicycle_id, people_id, status, location_id) VALUES (NOW(), :id, :people_id, "in", :location)', array('id'=>$_GET['return'],'people_id'=>$_GET['user'],'location'=>$_SESSION['filter2']['borrow']));

		db_query('UPDATE borrow_items SET location_id = :location WHERE id = :id', array('id'=>$_GET['return'], 'location'=>$_SESSION['filter2']['borrow']));

		redirect('?action=borrow');
	}

	if($_POST) {
		verify_campaccess_people(intval($_POST['people_id'][0]));
		verify_deletedrecord('people',intval($_POST['people_id'][0]));
		
		$table = 'borrow_transactions';
		$keys = array('transaction_date','bicycle_id','people_id','status','lights','helmet','location_id');
		
		db_query('UPDATE borrow_items SET location_id = :location WHERE id = :id', array('id'=>$_POST['bicycle_id'], 'location'=>$_SESSION['filter2']['borrow']));

		$handler = new formHandler($table);
		$handler->savePost($keys);

		redirect('?action='.$_POST['_origin']);
	}



	// open the template

	$data = db_row('SELECT b.*, bt.lights, bt.helmet, TIME_TO_SEC(TIMEDIFF(NOW(),transaction_date))>'.(intval($_SESSION['camp']['bicyclerenttime'])*3600).' AS toolate, CONCAT(HOUR(TIMEDIFF(NOW(),transaction_date)),":",LPAD(MINUTE(TIMEDIFF(NOW(),transaction_date)),2,"0")) AS duration, bt.transaction_date, bt.people_id, bt.status, CONCAT(firstname," ",lastname," (",container,")") AS user FROM borrow_items AS b LEFT OUTER JOIN borrow_transactions AS bt ON bt.bicycle_id = b.id LEFT OUTER JOIN people AS p ON bt.people_id = p.id WHERE b.id = :id ORDER BY transaction_date DESC ',array('id'=>$id));

	verify_campaccess_people($data['people_id']);
	verify_deletedrecord('people',$data['people_id']);

	if($data['status']=='out') {

		$cmsmain->assign('title',$data['user'].' is returning '.$data['label']);
		$cmsmain->assign('data',$data);
		$cmsmain->assign('include','borrow_return.tpl');
		
	} else {
		$visible = 	$data['visible'];
		$comment = 	$data['comment'];
			
		$data = db_row('SELECT * FROM borrow_items WHERE id = :id',array('id'=>$id));
		$data['bicycle_id'] = $id;
		$data['status'] = 'out';
		$data['category_id'] = db_value('SELECT category_id FROM borrow_items WHERE id = :id',array('id'=>$id));
		$data['transaction_date'] = strftime("%Y-%m-%d %H:%M:%S");
		$data['location_id'] = $_SESSION['filter2']['borrow'];

		if($visible) {
			$cmsmain->assign('title','Start a new rental for '.$data['label']);

			$translate['cms_form_submit'] = 'Start borrowing';
			$cmsmain->assign('translate',$translate);

			$time = strftime('%H')+(strftime('%M')/60);
			addfield('hidden','','location_id');
			
			if($data['category_id']==1 || $data['category_id']==3) {
				if($time>$endtime) {
					addfield('custom','&nbsp','<h2><span class="warning">After '.sprintf('%02d:%02d', (int) $endtime, fmod($endtime, 1) * 60).' we do not start new rentals!</span></h2>');
					$data['hidesubmit'] = true;
				} else {
					addfield('custom','&nbsp','<h3>Check the user\'s Bicycle Certificate, and make sure the user has a reflective vest, front light, helmet and key. <br />Asure the user to be back before '.strftime("%H:%M",strtotime('+2 Hours')).'</h3>');
					addfield('select','Find person','people_id',array('required'=>true, 'multiple'=>false, 'query'=>'SELECT p.id AS value, CONCAT(p.firstname, " ", p.lastname, " (", p.container, ")"," ",IF(bicycleban >= DATE_FORMAT(NOW(),"%Y-%m-%d"),CONCAT("(BAN UNTIL ",DATE_FORMAT(bicycleban,"%d-%m-%Y"),")"),"")) AS label, 
					IF(bicycleban >= DATE_FORMAT(NOW(),"%Y-%m-%d"),1,0) AS disabled FROM people AS p WHERE p.bicycletraining AND NOT p.deleted AND camp_id = '.$_SESSION['camp']['id'].' GROUP BY p.id ORDER BY SUBSTRING(REPLACE(container,"PK","Z"),1,1), SUBSTRING(REPLACE(container,"PK","Z"), 2, 10)*1'));
					addfield('checkbox','Rented out with helmet','helmet');
					addfield('checkbox','Rented out with lights','lights');
				}
			} else {
				addfield('custom','&nbsp','<h3>Take the resident\'s ID and make sure the resident is 16 or older<br />Asure the user to bring everything back before '.sprintf('%02d:%02d', (int) $endtime, fmod($endtime, 1) * 60).'</h3>');
				addfield('select','Find person','people_id',array('required'=>true, 'multiple'=>false, 'query'=>'SELECT p.id AS value, CONCAT(p.firstname, " ", p.lastname, " (", p.container, ")") AS label, NOT visible AS disabled FROM people AS p WHERE DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 >= 16 AND NOT p.deleted AND camp_id = '.$_SESSION['camp']['id'].' GROUP BY p.id ORDER BY SUBSTRING(REPLACE(container,"PK","Z"),1,1), SUBSTRING(REPLACE(container,"PK","Z"), 2, 10)*1'));
			}
			
		} else {

			$data['hidesubmit'] = true;
			addfield('custom','&nbsp',$data['comment']);
			$cmsmain->assign('title',$data['label'].' is not available for rent');
		}
		
		addfield('hidden','','bicycle_id');
		addfield('hidden','','status');
		addfield('hidden','','transaction_date');
		

		addfield('line','','');


		$cmsmain->assign('include','cms_form.tpl');
		$cmsmain->assign('data',$data);
		$cmsmain->assign('formelements',$formdata);
		
	}

