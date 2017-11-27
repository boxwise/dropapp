<?php
	$box['qr_id'] = db_value('SELECT id FROM qr WHERE code = :barcode',array('barcode'=>$_GET['newbox']));

	$data['products'] = db_array('SELECT p.id AS value, CONCAT(p.name, " " ,IFNULL(g.label,"")) AS label, sizegroup_id FROM products AS p LEFT OUTER JOIN genders AS g ON p.gender_id = g.id WHERE camp_id = :camp_id ORDER BY name',array('camp_id'=>$_SESSION['camp']['id']));
	$data['locations'] = db_array('SELECT *, id AS value FROM locations WHERE camp_id = :camp_id ORDER BY seq',array('camp_id'=>$_SESSION['camp']['id']));

	$data['allsizes'] = db_array('SELECT s.* FROM sizes AS s, sizegroup AS sg WHERE s.sizegroup_id = sg.id ORDER BY s.seq');

	$tpl->assign('box',$box);
	$tpl->assign('include','mobile_newbox.tpl');
