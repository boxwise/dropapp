<?php
	$table = 'stock';

	initlist();

	$cmsmain->assign('title','Container stock');
	listsetting('search', array('p.name'));

	$locations = join(',',db_simplearray('SELECT id, id FROM locations WHERE visible AND camp_id = :camp_id',array('camp_id'=>$_SESSION['camp']['id'])));

	$data = getlistdata('
SELECT
	CONCAT(p.id,"-",g.id,"-",s.id) AS id,
	p.name,
	g.label AS gender,
	s.label AS size,
	IFNULL(COUNT(s2.id),0) AS boxes,
	(SELECT COUNT(s3.id) FROM stock AS s3
	 LEFT OUTER JOIN locations AS l2 ON l2.id = s3.location_id
	 WHERE 
NOT s3.deleted AND s3.product_id = p.id AND p.gender_id = g.id AND s3.size_id = s.id AND l2.visible)-IFNULL(COUNT(s2.id),0) AS totalboxes, IFNULL(SUM(s2.items),0) AS stock
FROM
	(products AS p,
	sizes AS s)
LEFT OUTER JOIN genders AS g ON p.gender_id = g.id
LEFT OUTER JOIN stock AS s2 ON s2.product_id = p.id AND s2.size_id = s.id AND NOT s2.deleted AND s2.location_id IN (2,3)
WHERE
	NOT p.deleted AND
	s.sizegroup_id = p.sizegroup_id AND
	p.camp_id = '.$_SESSION['camp']['id'].' AND
	p.stockincontainer
GROUP BY
	p.name, g.label, s.id

UNION

SELECT
	CONCAT(p.id,"-",g.id,"-",s.id) AS id,
	p.name,
	g.label AS gender,
	s.label AS size,
	IFNULL(COUNT(s2.id),0) AS boxes,
	(SELECT COUNT(s3.id) FROM stock AS s3
	 LEFT OUTER JOIN locations AS l2 ON l2.id = s3.location_id
	 WHERE NOT s3.deleted AND s3.product_id = p.id AND p.gender_id = g.id AND s3.size_id = s.id AND l2.visible)-IFNULL(COUNT(s2.id),0) AS totalboxes,
	IFNULL(SUM(s2.items),0) AS stock
FROM
	(products AS p,
	sizes AS s)
LEFT OUTER JOIN genders AS g ON p.gender_id = g.id
LEFT OUTER JOIN stock AS s2 ON s2.product_id = p.id AND s2.size_id = s.id AND NOT s2.deleted AND s2.location_id IN (2,3)
WHERE
	NOT p.deleted AND
	s.sizegroup_id = p.sizegroup_id AND
	s2.location_id IN (2,3) AND
	NOT p.stockincontainer
GROUP BY
	p.name, g.label, s.id

');


	foreach($data as $key=>$d) {
		$totalboxes += $d['boxes'];
		$totalitems += $d['stock'];
	}

	listsetting('allowcopy', false);
	listsetting('allowadd', false);
	listsetting('allowdelete', false);
	listsetting('allowselect', false);
	listsetting('allowselectall', false);
	listsetting('allowsort', true);
	listsetting('maxheight', false);

	addcolumn('text','Product','name');
	addcolumn('text','Gender','gender');
	addcolumn('text','Size','size');
	addcolumn('text','Boxes','boxes');
	addcolumn('text','Items','stock');
	addcolumn('text','Boxes elsewhere','totalboxes');

	$cmsmain->assign('listfooter',array('Total boxes/items','','',$totalboxes,$totalitems, ''));

	$cmsmain->assign('data',$data);
	$cmsmain->assign('listconfig',$listconfig);
	$cmsmain->assign('listdata',$listdata);
	$cmsmain->assign('include','cms_list.tpl');
