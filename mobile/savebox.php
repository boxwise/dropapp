<?php

	if(!$_POST['box_id']) {
		do {
			$_POST['box_id'] = generateBoxID();
		} while(db_value('SELECT COUNT(id) FROM stock WHERE box_id = :box_id',array('box_id'=>$_POST['box_id'])));
	}

	$box = db_row('SELECT * FROM stock WHERE id = :id',array('id'=>$_POST['id']));
	if($box && $box['location_id']!=$_POST['location_id'][0]) {
		db_query('UPDATE stock SET ordered = NULL, ordered_by = NULL, picked = NULL, picked_by = NULL WHERE id = :id',array('id'=>$box['id']));
		db_query('INSERT INTO itemsout (product_id, size_id, count, movedate, from_location, to_location) VALUES ('.$box['product_id'].','.$box['size_id'].','.$box['items'].',NOW(),'.$box['location_id'].','.$_POST['location_id'][0].')');						
	}

	$handler = new formHandler('stock');

	$savekeys = array('box_id', 'product_id', 'size_id', 'items', 'location_id', 'comments' ,'qr_id');
	if($_POST['id']) $savekeys[] = 'id';
	$id = $handler->savePost($savekeys);
	db_query('UPDATE stock SET deleted = NULL WHERE id = :id',array('id'=>$id));


	$box = db_row('SELECT s.*, CONCAT(p.name," ",g.label) AS product, l.label AS location FROM stock AS s LEFT OUTER JOIN products AS p ON p.id = s.product_id LEFT OUTER JOIN genders AS g ON g.id = p.gender_id LEFT OUTER JOIN locations AS l ON l.id = s.location_id WHERE NOT s.deleted AND s.id = :id',array('id'=>$id));

	if($_POST['id']) {
		$message = 'Box '.$box['box_id'].' modified with '.$box['product'].' ('.$box['items'].'x) in '.$box['location'].'. <a href="?boxid='.$box['id'].'">Go back to this box.</a>';
	} else {
		$message = 'New box with box ID <strong class="bigger">'.$box['box_id'].'</strong> (write this number in the top right of the box label). This box contains '.$box['items'].' '.$box['product'].' and is located in '.$box['location'].'. <a href="?boxid='.$box['id'].'">Go to this box.</a>';
	}

	$data['barcode'] = db_value('SELECT code FROM qr WHERE id = :id',array('id'=>$box['qr_id']));

	redirect('?barcode='.$data['barcode'].'&message='.$message);
