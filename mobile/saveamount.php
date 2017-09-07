<?php
	$box = db_row('SELECT s.*, CONCAT(p.name," ",g.label) AS product, l.label AS location FROM stock AS s LEFT OUTER JOIN products AS p ON p.id = s.product_id LEFT OUTER JOIN genders AS g ON g.id = p.gender_id LEFT OUTER JOIN locations AS l ON l.id = s.location_id WHERE NOT s.deleted AND s.id = :box_id',array('box_id'=>$_GET['saveamount']));
	
	$newitems = max(0,$box['items'] - intval($_GET['items']));

	db_query('INSERT INTO history (tablename,record_id,changes,ip,changedate,user_id,from_int,to_int) VALUES ("stock",'.$box['id'].',"items","'.$_SERVER['REMOTE_ADDR'].'",NOW(),'.$_SESSION['user']['id'].', '.$box['items'].', '.$newitems.')');
	db_query('UPDATE stock SET items = :items, modified = NOW(), modified_by = :user WHERE id = :id',array('id'=>$box['id'],'items'=>$newitems,'user'=>$_SESSION['user']['id']));

	$market = db_value('SELECT id FROM locations WHERE is_market AND camp_id = :camp_id',array('camp_id'=>$_SESSION['camp']['id']));
	db_query('INSERT INTO itemsout (product_id, size_id, count, movedate, from_location, to_location) VALUES ('.$box['product_id'].','.$box['size_id'].','.intval($_GET['items']).',NOW(),'.$box['location_id'].','.$market.')');						



	$data['barcode'] = db_value('SELECT code FROM qr WHERE id = :id',array('id'=>$box['qr_id']));

	redirect('?message=Box <strong>'.$box['box_id'].'</strong> contains now '.$newitems.'x <strong>'.$box['product'].'</strong>. <a href="?boxid='.$box['id'].'">Go back to this box</a>.');
