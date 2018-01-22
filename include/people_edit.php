<?php
	$table = 'people';
	$action = 'people_edit';

	if($_POST) {
		
		if($_POST['pass']) $_POST['pass'] = md5($_POST['pass']);

		$handler = new formHandler($table);
		$handler->makeURL('fullname');

		if($_POST['id']) {
			$oldcontainer = db_value('SELECT container FROM people WHERE id = :id',array('id'=>$_POST['id']));
		}
 		$savekeys = array('firstname','lastname', 'gender', 'container', 'date_of_birth', 'email', 'pass', 'extraportion', 'comments', 'camp_id', 'bicycletraining', 'phone', 'notregistered', 'bicycleban', 'workshoptraining', 'workshopban','workshopsupervisor');
		if($_POST['pass']) $savekeys[] = 'pass';
		$id = $handler->savePost($savekeys);

		if($_POST['id'] && $oldcontainer != $_POST['container']) {
			if($_POST['parent_id']) {
				$parentcontainer = db_value('SELECT container FROM people WHERE parent_id = :id',array('id'=>$_POST['id']));
				if($parentcontainer != $_POST['container']) db_query('UPDATE people SET parent_id = 0 WHERE id = :id', array('id'=>$_POST['id']));
			} else {
				db_query('UPDATE people SET container = :container WHERE parent_id = :id', array('id'=>$_POST['id'], 'container'=>$_POST['container']));
			}
		}
		
		$postid = ($_POST['id']?$_POST['id']:$id);
		if (is_uploaded_file($_FILES['picture']['tmp_name'])) {
			if($_FILES['picture']['type']=='image/jpeg') {
				move_uploaded_file($_FILES['picture']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].'/uploads/people/'.$postid.'.jpg');
			}
		}
		if($_POST['picture_delete']) {
			unlink($_SERVER['DOCUMENT_ROOT'].'/uploads/people/'.$postid.'.jpg');
		}
		
		if($_POST['__action']=='submitandedit') redirect('?action='.$action.'&origin='.$_POST['_origin'].'&id='.$handler->id);
		redirect('?action='.$_POST['_origin']);
	}

	$data = db_row('SELECT * FROM '.$table.' WHERE id = :id',array('id'=>$id));

	if (!$id) {
		$data['visible'] = 1;
		$data['camp_id'] = $_SESSION['camp']['id'];
	}

	$cmsmain->assign('include','cms_form.tpl');
	if($data['firstname'] || $data['lastname']){
		$cmsmain->assign('title',$data['firstname'].' '.$data['lastname']);	
	} else {
		$cmsmain->assign('title', 'Add a new resident');
	}
	
	if($_SESSION['camp']['bicycle']||$_SESSION['camp']['workshop']) {
		$cmsmain->assign('tabs',array('people'=>'Personal','bicycle'=>($_SESSION['camp']['bicycle']?'Bicycle':'').($_SESSION['camp']['bicycle']&&$_SESSION['camp']['bicycle']?' & ':'').($_SESSION['camp']['workshop']?'Workshop':''),'transaction'=>'Transactions'));	
	} else {
		$cmsmain->assign('tabs',array('people'=>'Personal','transaction'=>'Transactions'))	;
	}

	$data['allowdrops'] = $_SESSION['user']['is_admin']||db_value('SELECT id FROM cms_functions AS f, cms_access AS a WHERE a.cms_functions_id = f.id AND f.include = "give2all" AND a.cms_users_id = :user_id',array('user_id'=>$_SESSION['user']['id']));

	if($data['parent_id'] == 0){
		if($id){
			$ajaxaside = new Zmarty;

			#$formdata = $formbuttons = '';

			$data['name'] = db_row('SELECT *, DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 AS age FROM people WHERE id = '. $id);

			$data['children'] = db_numrows('SELECT *, TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) AS age FROM people WHERE parent_id = :id AND TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) < '.$settings['adult-age'].' AND visible AND NOT deleted',array('id'=>$id));
			$data['children'] += db_numrows('SELECT *, TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) AS age FROM people WHERE id = :id AND TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) < '.$settings['adult-age'].' AND visible AND NOT deleted',array('id'=>$id));
			$data['adults'] = db_numrows('SELECT *, TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) AS age FROM people WHERE parent_id = :id AND TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) >= '.$settings['adult-age'].' AND visible AND NOT deleted',array('id'=>$id));
			$data['adults'] += db_numrows('SELECT *, TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) AS age FROM people WHERE id = :id AND TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) >= '.$settings['adult-age'].' AND visible AND NOT deleted',array('id'=>$id));

			$data['people'] = db_array('SELECT *, DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 AS age FROM people WHERE (parent_id = :id OR id = :id) AND NOT deleted ORDER BY parent_id, seq',array('id'=>$id));

		$adults = $settings['maxfooddrops_adult'] * db_value('SELECT SUM(IF((DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0) < 13, 0, 1)) AS adults FROM people WHERE id = :id OR parent_id = :id AND NOT deleted ',array('id'=>$id));
		$children = $settings['maxfooddrops_child'] * db_value('SELECT SUM(IF((DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0) < 13, 1, 0)) AS adults FROM people WHERE id = :id OR parent_id = :id AND NOT deleted ',array('id'=>$id));

		$data['fooddrops'] = intval($adults)+intval($children);
		$data['foodspent'] = db_value('SELECT SUM(drops) FROM transactions AS t, products AS p WHERE t.product_id = p.id AND p.category_id = 11 AND t.people_id = :id AND DATE_FORMAT(t.transaction_date,"%Y-%m-%d") = DATE_FORMAT(NOW(),"%Y-%m-%d")',array('id'=>$id));		
		$data['fooddrops'] += $data['foodspent'];


			$data['dropcoins'] = db_value('SELECT SUM(drops) FROM transactions AS t WHERE people_id = :id',array('id'=>$id));
			$data['givedropsurl'] = '?action=give&ids='.$id;

			$ajaxaside->assign('data',$data);
			$htmlaside = $ajaxaside->fetch('info_aside_purchase.tpl');

			addfield('html', '', $htmlaside, array('aside'=>true, 'asidetop'=>true));

		}


		addfield('line','','',array('aside'=>true));

	}

	addfield('hidden','camp_id','camp_id');
	addfield('hidden','parent_id','parent_id');
	addfield('text','Lastname','lastname',array('tab'=>'people'));
	addfield('text','Firstname','firstname',array('tab'=>'people','required'=>true));
	addfield('text',$_SESSION['camp']['familyidentifier'],'container',array('tab'=>'people','required'=>true));
	addfield('select','Gender','gender',array('tab'=>'people',
	'options'=>array(array('value'=>'M', 'label'=>'Male'), array('value'=>'F', 'label'=>'Female'))));

 	addfield('date','Date of birth','date_of_birth', array('tab'=>'people', 'date'=>true, 'time'=>false));
 	addfield('textarea','Comments','comments',array('tab'=>'people'));
	addfield('line','','',array('tab'=>'people'));
	addfield('checkbox','This person is not officially registered in camp','notregistered',array('tab'=>'people'));	
	if($settings['extraportion'] && $_SESSION['camp']['food']){
		addfield('checkbox','Extra food due to health condition (as indicated by Red Cross)','extraportion',array('tab'=>'people'));	
	}
	if($_SESSION['camp']['bicycle']||$_SESSION['camp']['workshop']){
		$data['picture'] = (file_exists($_SERVER['DOCUMENT_ROOT'].'/uploads/people/'.$id.'.jpg')?$id:0);
		if($data['picture']) {
			$exif = exif_read_data($_SERVER['DOCUMENT_ROOT'].'/uploads/people/'.$id.'.jpg');
			$data['rotate'] = ($exif['Orientation']==3?180:($exif['Orientation']==6?90:($exif['Orientation']==8?270:0)));
		}
		addfield('photo','Picture for cards','picture',array('tab'=>'bicycle'));
		addfield('line','','',array('tab'=>'bicycle'));
		addfield('text','Phone number','phone',array('tab'=>'bicycle'));
	}
	if($_SESSION['camp']['bicycle']){
		addfield('line','','',array('tab'=>'bicycle'));
		addfield('checkbox','This person succesfully passed the bicycle training', 'bicycletraining', array('tab'=>'bicycle'));
		addfield('date','Bicycle ban until','bicycleban',array('tab'=>'bicycle', 'time'=>false, 'date'=>true, 'tooltip'=>'Ban this person from the borrowing system until (and including) this date. Empty this field to cancel the ban.'));
		addfield('bicyclecard','Card','bicyclecard',array('tab'=>'bicycle'));
	}
	if($_SESSION['camp']['workshop']){
		addfield('line','','',array('tab'=>'bicycle'));
		addfield('checkbox','This person succesfully passed the workshop training', 'workshoptraining', array('tab'=>'bicycle'));
		addfield('checkbox','This person is a workshop supervisor', 'workshopsupervisor', array('tab'=>'bicycle'));
		addfield('date','Workshop ban until','workshopban',array('tab'=>'bicycle', 'time'=>false, 'date'=>true, 'tooltip'=>'Ban this person from the workshop until (and including) this date. Empty this field to cancel the ban.'));
		addfield('workshopcard','Card','workshopcard',array('tab'=>'bicycle'));
	}

	if($data['parent_id'] == 0){
		if($id){
			$table = 'transactions';
			addfield('list','Purchases','purch', array('tab'=>'transaction','width'=>10,'query'=>'SELECT t.*, u.naam AS user, CONCAT(IF(drops>0,"+",""),drops) AS drops2, DATE_FORMAT(transaction_date,"%d-%m-%Y %H:%i") AS tdate, CONCAT(p.name, " " ,IFNULL(g.label,"")) AS product 
				FROM transactions AS t 
				LEFT OUTER JOIN cms_users AS u ON u.id = t.user_id 
				LEFT OUTER JOIN products AS p ON p.id = t.product_id 
				LEFT OUTER JOIN genders AS g ON p.gender_id = g.id 
				WHERE people_id = '.$id. ' AND t.product_id != 0 
				ORDER BY transaction_date DESC
				LIMIT 20', 
				'columns'=>array('product'=>'Product', 'count'=>'Amount', 'drops2'=>ucwords($translate['market_coins']), 'description'=>'Note','user'=>'Purchase made by', 'tdate'=>'Date'),
		'allowedit'=>false,'allowadd'=>true, 'add'=>'New Purchase', 'addaction'=>'check_out&people_id='.intval($id),'allowselect'=>true,'allowselectall'=>false, 'action'=>'transactions', 'redirect'=>true, 'allowsort'=>false, 'modal'=>false));

			addfield('line','','',array('tab'=>'transaction'));

			$table = 'transactions';
			addfield('list','Transactions','trans', array('tab'=>'transaction','width'=>10,'query'=>'SELECT t.*, u.naam AS user, CONCAT(IF(drops>0,"+",""),drops) AS drops2, DATE_FORMAT(transaction_date,"%d-%m-%Y %H:%i") AS tdate 
				FROM transactions AS t 
				LEFT OUTER JOIN cms_users AS u ON u.id = t.user_id 
				WHERE people_id = '.$id. ' AND t.product_id = 0 
				ORDER BY transaction_date DESC
				LIMIT 5', 
				'columns'=>array('drops2'=>ucwords($translate['market_coins']), 'description'=>'Note','user'=>'Transaction made by', 'tdate'=>'Date'),
		'allowedit'=>false,'allowadd'=>$data['allowdrops'], 'add'=>'Give '.ucwords($translate['market_coins']), 'addaction'=>'give&ids='.intval($id), 'allowsort'=>false,'allowselect'=>true,'allowselectall'=>false, 'action'=>'transactions', 'redirect'=>true, 'modal'=>false));



/*
			//show food history
			if(db_value('SELECT food FROM camps WHERE id ='.$_SESSION['camp']['id'])) {	
				addfield('list','Food','food', array('width'=>10,'query'=>'
					SELECT f.name AS food, ft.count, ft.created, u.naam AS user, DATE_FORMAT(ft.created,"%d-%m-%Y %H:%i") AS ftdate 
					FROM food_transactions AS ft 
					LEFT OUTER JOIN food AS f ON f.id = ft.food_id
					LEFT OUTER JOIN cms_users AS u ON u.id = ft.created_by 
					WHERE ft.people_id = '.$id.' 
					ORDER BY ft.created DESC
					LIMIT 10', 
					'columns'=>array('food'=>'Food', 'count'=>'Amount', 'user'=>'Transaction made by', 'ftdate'=>'Date'),
					'allowedit'=>false,'allowadd'=>false,'allowsort'=>false,'allowselect'=>false,'allowselectall'=>false,'redirect'=>false,'modal'=>false));
			}
*/
			//show borrow history
	addfield('line','','',array('tab'=>'bicycle'));
			if(db_value('SELECT id FROM borrow_transactions WHERE people_id ='.$id)) {	
				addfield('list','Last 10 transactions','bicycles', array('tab'=>'bicycle','width'=>10,'query'=>'
					SELECT DATE_FORMAT(transaction_date,"%e-%m-%Y %H:%i:%S") AS dateout, 
(SELECT DATE_FORMAT(transaction_date,"%e-%m-%Y %H:%i:%S") FROM borrow_transactions AS t2 WHERE t.bicycle_id = t2.bicycle_id AND t2.transaction_date > t.transaction_date ORDER BY transaction_date LIMIT 1) AS datein, (SELECT label FROM borrow_items WHERE id = t.bicycle_id) AS name FROM borrow_transactions AS t LEFT OUTER JOIN people AS p ON p.id = t.people_id WHERE p.id = '.intval($id).' AND status = "out" ORDER BY transaction_date DESC LIMIT 10', 
					'columns'=>array('name'=>'Bicycle', 'dateout'=>'Start', 'datein'=>'End'),
					'allowedit'=>false,'allowadd'=>false,'allowsort'=>false,'allowselect'=>false,'allowselectall'=>false,'redirect'=>false,'modal'=>false));
			}			

		}


	}
	addfield('created','Created','created',array('aside'=>true));


	if ($id) addformbutton('submitandedit',$translate['cms_form_save']);

	$cmsmain->assign('data',$data);
	$cmsmain->assign('formelements',$formdata);
	$cmsmain->assign('formbuttons',$formbuttons);
