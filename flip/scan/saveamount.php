<?
	$box = db_row('SELECT s.*, CONCAT(p.name," ",g.label) AS product, l.label AS location FROM stock AS s LEFT OUTER JOIN products AS p ON p.id = s.product_id LEFT OUTER JOIN genders AS g ON g.id = p.gender_id LEFT OUTER JOIN locations AS l ON l.id = s.location_id WHERE NOT s.deleted AND s.id = :box_id',array('box_id'=>$_GET['saveamount']));

	db_query('INSERT INTO history (tablename,record_id,changes,ip,changedate) VALUES ("stock",'.$box['id'].',"Changed number of items from '.$box['items'].' to '.intval($_GET['items']).'","'.$_SERVER['REMOTE_ADDR'].'",NOW())');
	db_query('UPDATE stock SET items = :items, modified = NOW() WHERE id = :id',array('id'=>$box['id'],'items'=>$_GET['items']));

	$data['barcode'] = db_value('SELECT code FROM qr WHERE id = :id',array('id'=>$box['qr_id']));

	redirect('?message=Box <strong>'.$box['box_id'].'</strong> contains now '.intval($_GET['items']).'x <strong>'.$box['product'].'</strong>');