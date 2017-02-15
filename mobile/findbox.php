<?php

	$barcode = db_row('SELECT q.code AS code, s.id AS id FROM (stock AS s, locations AS l) LEFT OUTER JOIN qr AS q ON q.id = s.qr_id WHERE s.location_id = l.id AND box_id = :box_id AND l.camp_id = :camp_id', array('camp_id'=>$_SESSION['camp']['id'],'box_id'=>$_GET['findbox']));

	if($barcode['code']) {
		redirect('?barcode='.$barcode['code']);
	} elseif($barcode['id']) {
		redirect('?boxid='.$barcode['id']);
	} else {
		redirect('?barcode=&warning=1&message=This box number does not exist.');
	}
	die();
