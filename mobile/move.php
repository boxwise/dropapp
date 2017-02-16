<?php

	$move = intval($_GET['move']);

	$box = db_row('SELECT s.*, CONCAT(p.name," ",g.label) AS product, l.label AS location FROM stock AS s LEFT OUTER JOIN products AS p ON p.id = s.product_id LEFT OUTER JOIN genders AS g ON g.id = p.gender_id LEFT OUTER JOIN locations AS l ON l.id = s.location_id WHERE NOT s.deleted AND s.id = :id',array('id'=>$move));

	$newlocation = db_row('SELECT * FROM locations AS l WHERE l.id = :location',array('location'=>intval($_GET['location'])));

	db_query('UPDATE stock SET location_id = :location_id, modified = NOW() WHERE id = :id',array('location_id'=>$newlocation['id'],'id'=>$box['id']));
	db_query('INSERT INTO history (tablename,record_id,changes,ip,changedate) VALUES ("stock",'.$box['id'].',"Box moved to '.$newlocation['label'].'","'.$_SERVER['REMOTE_ADDR'].'",NOW())');

	redirect('?message='.'Box <strong>'.$box['box_id'].'</strong> contains '.$box['items'].'x <strong>'.$box['product'].'</strong> is moved from <strong>'.$box['location'].'</strong> to <strong>'.$newlocation['label'].'</strong>. <a href="?boxid='.$box['id'].'">Go back to this box.</a>');
