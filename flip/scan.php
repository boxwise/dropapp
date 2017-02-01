<?
	include('flipsite.php');

	error_reporting(E_ALL);
	ini_set('display_errors',true);

	date_default_timezone_set('Europe/Athens');
	db_query('SET time_zone = "+02:00"');

	$tpl = new Zmarty;

	# Boxlabel is scanned 
	if($_GET['barcode']!='') {
		$data['barcode'] = $_GET['barcode'];
		
		if(!db_value('SELECT id FROM qr WHERE code = :code',array('code'=>$_GET['barcode']))) {
			$data['message'] = 'This is not a valid QR-code for Drop In The Ocean';
			$data['barcode'] = '';
			$tpl->assign('include','mobile_message.tpl');
		} else {
			$box = db_row('SELECT s.*, CONCAT(p.name," ",g.label," ",IFNULL(s2.label, "")) AS product, l.label AS location FROM stock AS s 
	LEFT OUTER JOIN products AS p ON p.id = s.product_id 
	LEFT OUTER JOIN genders AS g ON g.id = p.gender_id 
	LEFT OUTER JOIN sizes AS s2 ON s2.id = s.size_id
	LEFT OUTER JOIN locations AS l ON l.id = s.location_id 
	LEFT OUTER JOIN qr AS q ON q.id = s.qr_id 
	WHERE NOT s.deleted AND q.code = :barcode',array('barcode'=>$data['barcode']));
			
			$locations = db_array('SELECT id AS value, label, IF(id = :location, true, false) AS selected FROM locations ORDER BY seq',array('location'=>$box['location_id']));
	
			$tpl->assign('box',$box);
			$tpl->assign('locations',$locations);
			$tpl->assign('include','mobile_scan.tpl');
		}

	# Assign a QR code to existing box
	} elseif($_GET['assignbox']!='') {
		$data['barcode'] = $_GET['assignbox'];
		
		$data['stock'] = db_array('SELECT s.id, CONCAT(s.box_id," - ",s.items,"x ",IFNULL(p.name,"")," ",IFNULL(g.label,""),IF(s2.label IS NULL,"",CONCAT(" (",s2.label,")"))) AS label FROM stock AS s 
LEFT OUTER JOIN products AS p ON p.id = s.product_id 
LEFT OUTER JOIN genders AS g ON g.id = p.gender_id
LEFT OUTER JOIN sizes AS s2 ON s2.id = s.size_id
LEFT OUTER JOIN locations AS l ON l.id = s.location_id
WHERE NOT s.deleted AND l.visible AND (s.qr_id IS NULL OR s.qr_id = 0)
ORDER BY s.box_id');

		$tpl->assign('include','mobile_assign.tpl');

	# Save assignbox selection
	} elseif($_GET['saveassignbox']!='') {
		$qrid = db_value('SELECT id FROM qr WHERE code = :barcode',array('barcode'=>$_GET['saveassignbox']));
		
		if(!intval($_GET['box'])) die('Missing Box ID');
		db_query('UPDATE stock SET qr_id = :qrid, modified = NOW() WHERE id = :boxid',array('qrid'=>$qrid,'boxid'=>$_GET['box']));

		$box = db_row('SELECT s.*, CONCAT(p.name," ",g.label) AS product, l.label AS location FROM stock AS s LEFT OUTER JOIN products AS p ON p.id = s.product_id LEFT OUTER JOIN genders AS g ON g.id = p.gender_id LEFT OUTER JOIN locations AS l ON l.id = s.location_id WHERE NOT s.deleted AND s.id = :box_id',array('box_id'=>$_GET['box']));
				
		db_query('INSERT INTO history (tablename,record_id,changes,ip,changedate) VALUES ("stock",'.$box['id'].',"Box assigned to QR-code '.$qrid.'","'.$_SERVER['REMOTE_ADDR'].'",NOW())');
		
		
		$data['message'] = 'QR-code is succesfully linked to box '.$box['product'].' ('.$box['items'].'x) in '.$box['location'];
		$data['barcode'] = $_GET['saveassignbox'];
		$tpl->assign('include','mobile_message.tpl');

		
	# Make a new box with this QR code
	} elseif($_GET['newbox']!='') {
		$box['qr_id'] = db_value('SELECT id FROM qr WHERE code = :barcode',array('barcode'=>$_GET['newbox']));
				
		$i=1;
		while(db_value('SELECT box_id FROM stock WHERE box_id = :id',array('id'=>str_pad($i,2,'0',STR_PAD_LEFT).strftime('%d%m')))) {
			$i++;
		}

		$box['box_id'] = str_pad($i,2,'0',STR_PAD_LEFT).strftime('%d%m');

		$data['products'] = db_array('SELECT p.id AS value, CONCAT(p.name, " " ,IFNULL(g.label,"")) AS label FROM products AS p LEFT OUTER JOIN genders AS g ON p.gender_id = g.id WHERE NOT p.deleted ORDER BY name');
		$data['locations'] = db_array('SELECT *, id AS value FROM locations ORDER BY seq');
		
		$tpl->assign('box',$box);
		$tpl->assign('include','mobile_newbox.tpl');
	
	# Edit the info for existing box
	} elseif($_GET['editbox']!='') {
		$box = db_row('SELECT s.*, CONCAT(p.name," ",g.label) AS product, l.label AS location FROM stock AS s LEFT OUTER JOIN products AS p ON p.id = s.product_id LEFT OUTER JOIN genders AS g ON g.id = p.gender_id LEFT OUTER JOIN locations AS l ON l.id = s.location_id WHERE NOT s.deleted AND s.id = :id',array('id'=>$_GET['editbox']));

		$data['products'] = db_array('SELECT p.id AS value, CONCAT(p.name, " " ,IFNULL(g.label,"")) AS label FROM products AS p LEFT OUTER JOIN genders AS g ON p.gender_id = g.id WHERE NOT p.deleted ORDER BY name');
		$data['locations'] = db_array('SELECT *, id AS value FROM locations ORDER BY seq');

/*
		$data['qrid'] = db_value('SELECT id FROM qr WHERE code = :barcode',array('barcode'=>$_GET['newbox']));
				
		$i=1;
		while(db_value('SELECT box_id FROM stock WHERE box_id = :id',array('id'=>str_pad($i,2,'0',STR_PAD_LEFT).strftime('%d%m')))) {
			$i++;
		}

		$data['box_id'] = str_pad($i,2,'0',STR_PAD_LEFT).strftime('%d%m');

*/
		
		$tpl->assign('box',$box);
		$tpl->assign('include','mobile_newbox.tpl');
	
	# Save a new box with this QR code
	} elseif($_GET['savebox']!='') {
		
		$handler = new formHandler('stock');

		$savekeys = array('box_id', 'product_id', 'size_id', 'items', 'location_id', 'comments' ,'qr_id');
		if($_POST['id']) $savekeys[] = 'id';
		$id = $handler->savePost($savekeys);
		
		
		$box = db_row('SELECT s.*, CONCAT(p.name," ",g.label) AS product, l.label AS location FROM stock AS s LEFT OUTER JOIN products AS p ON p.id = s.product_id LEFT OUTER JOIN genders AS g ON g.id = p.gender_id LEFT OUTER JOIN locations AS l ON l.id = s.location_id WHERE NOT s.deleted AND s.id = :box_id',array('box_id'=>$id));

		if($_POST['id']) {
			$data['message'] = 'Box '.$box['box_id'].' modified with '.$box['product'].' ('.$box['items'].'x) in '.$box['location'];
		} else {
			$data['message'] = 'New box '.$box['box_id'].' created with '.$box['product'].' ('.$box['items'].'x) in '.$box['location'];			
		}
		$data['barcode'] = db_value('SELECT code FROM qr WHERE id = :id',array('id'=>$box['qr_id']));
		$tpl->assign('include','mobile_message.tpl');

	
	# Move this box to a new location
	} elseif($_GET['move']!='') {
		$move = intval($_GET['move']);
		
		$box = db_row('SELECT s.*, CONCAT(p.name," ",g.label) AS product, l.label AS location FROM stock AS s LEFT OUTER JOIN products AS p ON p.id = s.product_id LEFT OUTER JOIN genders AS g ON g.id = p.gender_id LEFT OUTER JOIN locations AS l ON l.id = s.location_id WHERE NOT s.deleted AND s.id = :id',array('id'=>$move));
		
		$newlocation = db_row('SELECT * FROM locations AS l WHERE l.id = :location',array('location'=>intval($_GET['location'])));

		db_query('UPDATE stock SET location_id = :location_id, modified = NOW() WHERE id = :id',array('location_id'=>$newlocation['id'],'id'=>$box['id']));
		db_query('INSERT INTO history (tablename,record_id,changes,ip,changedate) VALUES ("stock",'.$box['id'].',"Box moved to '.$newlocation['label'].'","'.$_SERVER['REMOTE_ADDR'].'",NOW())');
		
		$data['message'] = 'Box <strong>'.$box['box_id'].'</strong> contains '.$box['items'].'x <strong>'.$box['product'].'</strong><br />is moved from <strong>'.$box['location'].'</strong> to <strong>'.$newlocation['label'];
		$data['barcode'] = db_value('SELECT code FROM qr WHERE id = :id',array('id'=>$box['qr_id']));
		$tpl->assign('include','mobile_message.tpl');

	# Change the amount of items in this box
	} elseif($_GET['changeamount']!='') {

		$box = db_row('SELECT s.*, CONCAT(p.name," ",g.label) AS product, l.label AS location FROM stock AS s LEFT OUTER JOIN products AS p ON p.id = s.product_id LEFT OUTER JOIN genders AS g ON g.id = p.gender_id LEFT OUTER JOIN locations AS l ON l.id = s.location_id WHERE s.id = :id',array('id'=>$_GET['changeamount']));

		$tpl->assign('box',$box);
		$tpl->assign('include','mobile_amount.tpl');

	# Save the new amount of items in this box
	} elseif($_GET['saveamount']!='') {
		$box = db_row('SELECT s.*, CONCAT(p.name," ",g.label) AS product, l.label AS location FROM stock AS s LEFT OUTER JOIN products AS p ON p.id = s.product_id LEFT OUTER JOIN genders AS g ON g.id = p.gender_id LEFT OUTER JOIN locations AS l ON l.id = s.location_id WHERE NOT s.deleted AND s.id = :box_id',array('box_id'=>$_GET['saveamount']));

		db_query('INSERT INTO history (tablename,record_id,changes,ip,changedate) VALUES ("stock",'.$box['id'].',"Changed number of items from '.$box['items'].' to '.intval($_GET['items']).'","'.$_SERVER['REMOTE_ADDR'].'",NOW())');
		db_query('UPDATE stock SET items = :items, modified = NOW() WHERE id = :id',array('id'=>$box['id'],'items'=>$_GET['items']));

		$data['barcode'] = db_value('SELECT code FROM qr WHERE id = :id',array('id'=>$box['qr_id']));
		$data['message'] = 'Box <strong>'.$box['box_id'].'</strong> contains now '.intval($_GET['items']).'x <strong>'.$box['product'].'</strong>';
		$tpl->assign('include','mobile_message.tpl');

	}	

	$tpl->assign('data',$data);
/*
	$tpl->assign('newlocation',$newlocation);
*/
	
	$tpl->display('mobile.tpl');
