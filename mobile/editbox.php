<?php
	$box = db_row('SELECT s.*, CONCAT(p.name," ",g.label) AS product, l.label AS location FROM stock AS s LEFT OUTER JOIN products AS p ON p.id = s.product_id LEFT OUTER JOIN genders AS g ON g.id = p.gender_id LEFT OUTER JOIN locations AS l ON l.id = s.location_id WHERE s.id = :id',array('id'=>$_GET['editbox']));

	if($box['deleted']) {
		unset($box['location_id']);
		$data['warning'] = "This box has been deleted. Editing and saving this form undeletes it.";
	}
	
	$data['products'] = db_array('SELECT p.id AS value, CONCAT(p.name, " " ,IFNULL(g.label,"")) AS label, sizegroup_id FROM products AS p LEFT OUTER JOIN genders AS g ON p.gender_id = g.id WHERE NOT p.deleted AND p.camp_id = :camp_id ORDER BY name',array('camp_id'=>$_SESSION['camp']['id']));
	$data['locations'] = db_array('SELECT *, id AS value FROM locations WHERE camp_id = :camp_id ORDER BY seq',array('camp_id'=>$_SESSION['camp']['id']));
	$data['sizes'] = db_array('SELECT s.* FROM sizes AS s LEFT OUTER JOIN products AS p ON p.id = :product_id WHERE s.sizegroup_id = p.sizegroup_id ORDER BY s.seq
', array('product_id'=>$box['product_id']));

	$data['allsizes'] = db_array('SELECT s.* FROM sizes AS s, sizegroup AS sg WHERE s.sizegroup_id = sg.id ORDER BY s.seq');

	$tpl->assign('box',$box);
	$tpl->assign('include','mobile_newbox.tpl');
