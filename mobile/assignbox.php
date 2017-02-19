<?php
	$data['barcode'] = $_GET['assignbox'];

	$data['stock'] = db_array('SELECT s.id, CONCAT(s.box_id," - ",s.items,"x ",IFNULL(p.name,"")," ",IFNULL(g.label,""),IF(s2.label IS NULL,"",CONCAT(" (",s2.label,")"))) AS label FROM stock AS s
	LEFT OUTER JOIN products AS p ON p.id = s.product_id
	LEFT OUTER JOIN genders AS g ON g.id = p.gender_id
	LEFT OUTER JOIN sizes AS s2 ON s2.id = s.size_id
	LEFT OUTER JOIN locations AS l ON l.id = s.location_id
	WHERE NOT s.deleted AND l.visible AND (s.qr_id IS NULL OR s.qr_id = 0) AND l.camp_id = '.$_SESSION['camp']['id'].'
	ORDER BY s.box_id');

	$tpl->assign('include','mobile_assign.tpl');
