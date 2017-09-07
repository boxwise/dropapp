<?php

	if($_GET['picked']) {
		db_query('UPDATE stock SET ordered = NULL, ordered_by = NULL, picked = NOW(), picked_by = :user WHERE id = :id',array('id'=>intval($_GET['picked']),'user'=>$_SESSION['user']['id']));
		simpleSaveChangeHistory('stock', $_GET['picked'], 'Box picked to bring to warehouse ');
	}
	
	$boxes = db_array('
SELECT s.id, l.label AS location, s.box_id, p.name AS product, s.items, si.label AS size, g.label AS gender FROM stock AS s 
LEFT OUTER JOIN locations AS l ON s.location_id = l.id
LEFT OUTER JOIN products AS p ON s.product_id = p.id
LEFT OUTER JOIN genders AS g ON p.gender_id = g.id
LEFT OUTER JOIN sizes AS si ON s.size_id = si.id
WHERE l.camp_id = 1 AND NOT s.deleted AND s.ordered
ORDER BY l.id, s.box_id', array('camp'=>$_SESSION['camp']['id']));

	$tpl->assign('boxes',$boxes);
	
	$tpl->assign('include','mobile_vieworders.tpl');
