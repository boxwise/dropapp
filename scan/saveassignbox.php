<?
	$qrid = db_value('SELECT id FROM qr WHERE code = :barcode',array('barcode'=>$_GET['saveassignbox']));
	
	if(!intval($_GET['box'])) die('Missing Box ID');
	db_query('UPDATE stock SET qr_id = :qrid, modified = NOW() WHERE id = :boxid',array('qrid'=>$qrid,'boxid'=>$_GET['box']));
	
	$box = db_row('SELECT s.*, CONCAT(p.name," ",g.label) AS product, l.label AS location FROM stock AS s LEFT OUTER JOIN products AS p ON p.id = s.product_id LEFT OUTER JOIN genders AS g ON g.id = p.gender_id LEFT OUTER JOIN locations AS l ON l.id = s.location_id WHERE NOT s.deleted AND s.id = :box_id',array('box_id'=>$_GET['box']));
			
	db_query('INSERT INTO history (tablename,record_id,changes,ip,changedate) VALUES ("stock",'.$box['id'].',"Box assigned to QR-code '.$qrid.'","'.$_SERVER['REMOTE_ADDR'].'",NOW())');
	
	redirect('?message=QR-code is succesfully linked to box '.$box['product'].' ('.$box['items'].'x) in '.$box['location']);

	
	
