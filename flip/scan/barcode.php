<?
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
		
		$locations = db_array('SELECT id AS value, label, IF(id = :location, true, false) AS selected FROM locations WHERE camp_id = :camp_id ORDER BY seq',array('camp_id'=>$_SESSION['camp']['id'],'location'=>$box['location_id']));
		$tpl->assign('box',$box);
		$tpl->assign('locations',$locations);
		$tpl->assign('include','mobile_scan.tpl');
	}
