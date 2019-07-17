<?php

	$move = intval($_GET['move']);

	$box = db_row('SELECT s.*, CONCAT(p.name," ",g.label) AS product,s.comments as comment, l.label AS location, s.location_id AS location_id FROM stock AS s LEFT OUTER JOIN products AS p ON p.id = s.product_id LEFT OUTER JOIN genders AS g ON g.id = p.gender_id LEFT OUTER JOIN locations AS l ON l.id = s.location_id WHERE (NOT s.deleted OR s.deleted IS NULL) AND s.id = :id',array('id'=>$move));

	$newlocation = db_row('SELECT * FROM locations AS l WHERE l.id = :location',array('location'=>intval($_GET['location'])));

	db_query('UPDATE stock SET location_id = :location_id, modified = NOW(), modified_by = :user, ordered = NULL, ordered_by = NULL, picked = NULL, picked_by = NULL WHERE id = :id',array('location_id'=>$newlocation['id'],'id'=>$box['id'],'user'=>$_SESSION['user']['id']));
	db_query('INSERT INTO history (tablename,record_id,changes,ip,changedate,user_id,from_int,to_int) VALUES ("stock",'.$box['id'].', "location_id", "'.$_SERVER['REMOTE_ADDR'].'",NOW(),'.$_SESSION['user']['id'].', '.$box['location_id'].', '.$newlocation['id'].')');

	if($box['location_id']!=$newlocation['id']) {
		db_query('INSERT INTO itemsout (product_id, size_id, count, movedate, from_location, to_location) VALUES ('.$box['product_id'].','.$box['size_id'].','.$box['items'].',NOW(),'.$box['location_id'].','.$newlocation['id'].')');						
	}


	redirect('?message='.'Box <strong>'.$box['box_id'].'</strong> contains '.$box['items'].'x <strong>'.$box['product'].'</strong> is moved from <strong>'.$box['location'].'</strong> to <strong>'.$newlocation['label'].'</strong>. <a href="?boxid='.$box['id'].'">Go back to this box.</a>');
