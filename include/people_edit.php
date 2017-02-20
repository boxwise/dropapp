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
 		$savekeys = array('firstname','lastname', 'gender', 'container', 'date_of_birth', 'email', 'pass', 'comments','camp_id');
		if($_POST['pass']) $savekeys[] = 'pass';
		$handler->savePost($savekeys);

		if($_POST['id'] && $oldcontainer != $_POST['container']) {
			if($_POST['parent_id']) {
				$parentcontainer = db_value('SELECT container FROM people WHERE parent_id = :id',array('id'=>$_POST['id']));
				if($parentcontainer != $_POST['container']) db_query('UPDATE people SET parent_id = 0 WHERE id = :id', array('id'=>$_POST['id']));
			} else {
				db_query('UPDATE people SET container = :container WHERE parent_id = :id', array('id'=>$_POST['id'], 'container'=>$_POST['container']));
			}
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
	$cmsmain->assign('title',$data['firstname'].' '.$data['lastname']);

	$data['allowdrops'] = $_SESSION['user']['is_admin']||db_value('SELECT id FROM cms_functions AS f, cms_access AS a WHERE a.cms_functions_id = f.id AND f.include = "give2all" AND a.cms_users_id = :user_id',array('user_id'=>$_SESSION['user']['id']));

/*
	if($data['parent_id'] == 0){
		if($id){
			$ajaxaside = new Zmarty;

			$formdata = $formbuttons = '';

			$data['name'] = db_row('SELECT *, DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 AS age FROM people WHERE id = '. $id);

			$data['children'] = db_numrows('SELECT *, TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) AS age FROM people WHERE parent_id = :id AND TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) < '.$settings['adult-age'].' AND visible AND NOT deleted',array('id'=>$id));
			$data['children'] += db_numrows('SELECT *, TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) AS age FROM people WHERE id = :id AND TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) < '.$settings['adult-age'].' AND visible AND NOT deleted',array('id'=>$id));
			$data['adults'] = db_numrows('SELECT *, TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) AS age FROM people WHERE parent_id = :id AND TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) >= '.$settings['adult-age'].' AND visible AND NOT deleted',array('id'=>$id));
			$data['adults'] += db_numrows('SELECT *, TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) AS age FROM people WHERE id = :id AND TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) >= '.$settings['adult-age'].' AND visible AND NOT deleted',array('id'=>$id));

			$data['people'] = db_array('SELECT *, DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 AS age FROM people WHERE parent_id = :id OR id = :id AND visible AND NOT deleted ORDER BY parent_id, seq',array('id'=>$id));

			$data['dropcoins'] = db_value('SELECT SUM(drops) FROM transactions AS t WHERE people_id = :id',array('id'=>$id));
			$data['givedropsurl'] = '?action=give&ids='.$id;

			$ajaxaside->assign('data',$data);
			$htmlaside = $ajaxaside->fetch('info_aside_purchase.tpl');

			addfield('html', '', $htmlaside, array('aside'=>true, 'asidetop'=>true));

		}


		addfield('line','','',array('aside'=>true));
		addfield('created','Created','created',array('aside'=>true));

	}
*/

	addfield('hidden','camp_id','camp_id');
	addfield('hidden','parent_id','parent_id');
	addfield('text','Lastname','lastname');
	addfield('text','Firstname','firstname',array('required'=>true));
	addfield('text','Container','container',array('required'=>true));
	addfield('select','Gender','gender',array(
	'options'=>array(array('value'=>'M', 'label'=>'Male'),array('value'=>'F', 'label'=>'Female'))));

 	addfield('date','Date of birth','date_of_birth', array('date'=>true, 'time'=>false));
	addfield('line');
	addfield('textarea','Comments','comments');
 	addfield('line');

	if($data['parent_id'] == 0){
		if($id){
			$table = 'transactions';
			addfield('list','Purchases','purch', array('width'=>10,'query'=>'SELECT t.*, u.naam AS user, CONCAT(IF(drops>0,"+",""),drops) AS drops2, DATE_FORMAT(transaction_date,"%d-%m-%Y %H:%i") AS tdate, CONCAT(p.name, " " ,IFNULL(g.label,"")) AS product FROM transactions AS t LEFT OUTER JOIN cms_users AS u ON u.id = t.user_id LEFT OUTER JOIN products AS p ON p.id = t.product_id LEFT OUTER JOIN genders AS g ON p.gender_id = g.id WHERE people_id = '.$id. ' AND t.product_id != 0 ORDER BY transaction_date DESC', 'columns'=>array('product'=>'Product', 'count'=>'Amount', 'drops2'=>'Drop Coins', 'description'=>'Note','user'=>'Purchase made by', 'tdate'=>'Date'),
		'allowedit'=>false,'allowadd'=>true, 'add'=>'New Purchase', 'addaction'=>'purchase&people_id='.intval($id),'allowselect'=>true,'allowselectall'=>false, 'action'=>'transactions', 'redirect'=>true, 'allowsort'=>false, 'modal'=>false));

			addfield('line','','');

			$table = 'transactions';
			addfield('list','Transactions','trans', array('width'=>10,'query'=>'SELECT t.*, u.naam AS user, CONCAT(IF(drops>0,"+",""),drops) AS drops2, DATE_FORMAT(transaction_date,"%d-%m-%Y %H:%i") AS tdate FROM transactions AS t LEFT OUTER JOIN cms_users AS u ON u.id = t.user_id WHERE people_id = '.$id. ' AND t.product_id = 0 ORDER BY transaction_date DESC', 'columns'=>array('drops2'=>'Drop Coins', 'description'=>'Note','user'=>'Transaction made by', 'tdate'=>'Date'),
		'allowedit'=>false,'allowadd'=>$data['allowdrops'], 'add'=>'Give Drops', 'addaction'=>'give&ids='.intval($id), 'allowsort'=>false,'allowselect'=>true,'allowselectall'=>false, 'action'=>'transactions', 'redirect'=>true, 'modal'=>false));


		}

		addfield('created','Created','created',array('aside'=>true));

	}


	if ($id) addformbutton('submitandedit',$translate['cms_form_save']);

	$cmsmain->assign('data',$data);
	$cmsmain->assign('formelements',$formdata);
	$cmsmain->assign('formbuttons',$formbuttons);
