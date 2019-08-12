<?php

	$table = 'stock';
	$action = 'stock_edit';

	if($_POST) {

		if(!$_POST['box_id']) {
			$newbox = true;
			do {
				$_POST['box_id'] = generateBoxID();
			} while(db_value('SELECT COUNT(id) FROM stock WHERE box_id = :box_id',array('box_id'=>$_POST['box_id'])));

		}
		$box = db_row('SELECT * FROM stock WHERE id = :id',array('id'=>$_POST['id']));
		if($box && ($box['location_id']!=$_POST['location_id'][0])) {
			db_query('UPDATE stock SET ordered = NULL, ordered_by = NULL, picked = NULL, picked_by = NULL WHERE id = :id',array('id'=>$_POST['id']));
			db_query('INSERT INTO itemsout (product_id, size_id, count, movedate, from_location, to_location) VALUES ('.$box['product_id'].','.$box['size_id'].','.$box['items'].',NOW(),'.$box['location_id'].','.$_POST['location_id'][0].')');						
		}
		
		$handler = new formHandler($table);

		$savekeys = array('box_id', 'product_id', 'size_id', 'items', 'location_id', 'comments');
		$id = $handler->savePost($savekeys);

		if($_POST['__action']=='submitandnew') {$created = True;
												redirect('?action=stock_edit&created='.$created.'&created_id='.$id);}

		if($newbox) {
			redirect('?action=stock_confirm&id='.$id);
		} else {
			redirect('?action='.$_POST['_origin']);
		}
	
	}
	
	if($_GET['created']==1){
	$smarty = new Zmarty;
	$box = db_row('SELECT s.*, CONCAT(p.name," ",g.label) AS product, l.label AS location FROM stock AS s LEFT OUTER JOIN products AS p ON p.id = s.product_id LEFT OUTER JOIN genders AS g ON g.id = p.gender_id LEFT OUTER JOIN locations AS l ON l.id = s.location_id WHERE (NOT s.deleted OR s.deleted IS NULL) AND s.id = :id',array('id'=>$_GET['created_id']));
	$smarty->assign('box',$box);
	$htmlaside = $smarty->fetch('stock_confirm_new.tpl');
	addfield('html', '', $htmlaside);

	}

	$data = db_row('SELECT * FROM '.$table.' WHERE id = :id',array('id'=>$id));
	verify_campaccess_location($data['location_id']);

	if (!$id) {
		$data['visible'] = 1;
	}

	// open the template
	$cmsmain->assign('include','cms_form.tpl');
	addfield('hidden','','id');
	// put a title above the form
	$cmsmain->assign('title','Box');

	if($id) {
		addfield('text','Box ID','box_id',array('readonly'=>true,'width'=>2));
		addfield('line');
	}

	addfield('select','Product','product_id',array('required'=>true,'multiple'=>false,'query'=>'SELECT p.id AS value, CONCAT(p.name, " " ,IFNULL(g.label,"")) AS label FROM products AS p LEFT OUTER JOIN genders AS g ON p.gender_id = g.id WHERE (NOT p.deleted OR p.deleted IS NULL) AND p.camp_id = '.$_SESSION['camp']['id'].' ORDER BY name', 'onchange'=>'getSizes()'));

	addfield('select', 'Size', 'size_id', array('required'=>true,'width'=>2, 'multiple'=>false, 'query'=>'SELECT *, id AS value FROM sizes WHERE sizegroup_id = '.intval(db_value('SELECT sizegroup_id FROM products WHERE id = :id',array('id'=>$data['product_id']))).' ORDER BY seq','tooltip'=>'If the right size for your box is not here, don\'t put it in comments, but first double check if you have the right product. For example: Long sleeves for babies, we call them tops.'));

	addfield('number','Items','items');

	addfield('select', 'Location', 'location_id', array('required'=>true,'width'=>2, 'multiple'=>false, 'query'=>'SELECT *, id AS value FROM locations WHERE camp_id = '.$_SESSION['camp']['id'].' ORDER BY seq'));

	if($data['qr_id']){
		$qr = db_value('SELECT code FROM qr WHERE id = :id',array('id'=>$data['qr_id']));

		addfield('html', '', '<img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=http://'.$_SERVER['HTTP_HOST'].'/mobile.php?barcode='.$qr.'" /><br /><br />', array('aside'=>true, 'asidetop'=>true));
	}

	addfield('line');
	addfield('textarea','Comments','comments');

/*
	#these where added for the conversion from the google sheet
	addfield('line');
	addfield('text','Old type','_type',array('readonly'=>true));
	addfield('text','Old gender','_gender',array('readonly'=>true));
	addfield('text','Old size','_size',array('readonly'=>true));
*/

	addfield('line','','',array('aside'=>true));
	addfield('created','Created','created',array('aside'=>true));

	addformbutton('submitandnew','Save and new item');

	// place the form elements and data in the template
	$cmsmain->assign('data',$data);
	$cmsmain->assign('formelements',$formdata);
	$cmsmain->assign('formbuttons',$formbuttons);

	function generateBoxID($length = 6, $possible = '0123456789') {
		$password = "";
	 	$i = 0;
		while ($i < $length) {
			$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
			if (!strstr($password, $char)) {
				$password .= $char;
				$i++;
			}
		}
		return $password;
	}
