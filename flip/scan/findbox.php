<?
		
	$barcode = db_value('SELECT q.code FROM stock AS s, locations AS l, qr AS q WHERE q.id = s.qr_id AND s.location_id = l.id AND box_id = :box_id AND l.camp_id = :camp_id', array('camp_id'=>$_SESSION['camp']['id'],'box_id'=>$_GET['findbox']));
	
	redirect('?barcode='.$barcode);
	die();