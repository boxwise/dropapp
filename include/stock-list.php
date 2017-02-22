<?php

	$table = 'stock';
	$action = 'stock-list';

	if(!isset($_SESSION['filter']['stock-list'])) $_SESSION['filter']['stock-list'] = $_SESSION['camp']['id'];
	initlist();

	$cmsmain->assign('title','General stock');
	listsetting('search', array('p.name'));

	listfilter(array('label'=>'Camps','query'=>'SELECT id, name AS label FROM camps','filter'=>'p.camp_id'));

	$locations = join(',',db_simplearray('SELECT id, id FROM locations WHERE visible AND camp_id = :camp_id',array('camp_id'=>$_SESSION['filter']['stock-list'])));

// 	$camps = db_simplearray('SELECT id, name FROM camps WHERE id != :camp_id',array('camp_id'=>$_SESSION['camp']['id']));

	$query = '
SELECT
	CONCAT(p.id,"-",g.id,"-",IFNULL(s.id,"")) AS id,
	p.name AS product,
	g.label AS gender,
	s.label AS size,
	(SELECT SUM(items) FROM stock AS st, locations AS l WHERE st.size_id = s.id AND st.location_id = l.id AND NOT st.deleted AND l.visible AND st.product_id = p.id) AS stock,
	COALESCE( NULLIF( (SELECT COUNT(st.id) FROM stock AS st, locations AS l WHERE st.size_id = s.id AND st.location_id = l.id AND NOT st.deleted AND l.visible AND st.product_id = p.id),0),"") AS boxes, 

	ROUND((SELECT COUNT(id) FROM people AS p2 WHERE p2.visible AND NOT p2.deleted AND p2.camp_id = p.camp_id AND  
	(IF(g.male,p2.gender="M",0) OR IF(g.female,p2.gender="F",0)) AND 
	(IF(g.adult,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p2.date_of_birth)), "%Y")+0>=13,0) OR IF(g.baby,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p2.date_of_birth)), "%Y")+0<2,0) OR IF(g.child,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p2.date_of_birth)), "%Y")+0 BETWEEN 2 AND 13,0)))*IFNULL(s.portion,100)/100) AS target,
	p.amountneeded';	

/*
	foreach($camps as $key=>$camp) {
		$insert[] = '
	(SELECT SUM(items) FROM stock AS st, locations AS l, products AS p2 WHERE st.product_id = p2.id AND st.location_id = l.id AND l.visible AND NOT st.deleted AND p.name = p2.name AND st.size_id = s.id AND l.camp_id = '.$key.') AS "camp'.$key.'"';
	} 
	$query .= join(',',$insert);
*/

	$query .= '
FROM
	sizegroup AS sg,
	sizes AS s,
	products AS p,
	genders AS g
WHERE
	p.gender_id = g.id AND
	p.sizegroup_id = sg.id AND
	s.sizegroup_id = sg.id
ORDER BY 
	p.name, g.label, s.label';
	
	$data = getlistdata($query);

	foreach($data as $key=>$d) {
		$data[$key]['result'] = ($d['stock']/$d['target']*$d['amountneeded']/10);
	}

	listsetting('allowcopy', false);
	listsetting('allowadd', false);
	listsetting('allowdelete', false);
	listsetting('allowselect', false);
	listsetting('allowselectall', false);
	listsetting('allowsort', true);
#	listsetting('width', 12);

	addcolumn('text','Product','product');
	addcolumn('text','Gender','gender');
	addcolumn('text','Size','size');
	addcolumn('text','Items','stock');
	addcolumn('text','Boxes','boxes');
	if(db_value('SELECT market FROM camps WHERE id = :camp', array('camp'=>$_SESSION['filter']['stock-list']))) {
		addcolumn('text','People','target');
#		addcolumn('text','','result');
		addcolumn('bar','Score','result2');
	}
/*
	foreach($camps as $key=>$camp) {
		addcolumn('text',$camp,'camp'.$key);
	}
*/
	
	$cmsmain->assign('data',$data);
	$cmsmain->assign('listconfig',$listconfig);
	$cmsmain->assign('listdata',$listdata);
	$cmsmain->assign('include','cms_list.tpl');

