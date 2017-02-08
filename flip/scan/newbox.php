<?
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

