<?php

	$table = 'stock';
	$action = 'stock-list';

	initlist();

	$cmsmain->assign('title','General stock');
	listsetting('search', array('p.name'));

 	$camps = db_simplearray('SELECT id, name FROM camps WHERE id != :camp_id',array('camp_id'=>$_SESSION['camp']['id']));

	$query = 'SELECT CONCAT(p.id,"-",g.id,"-",IFNULL(s.id,"")) AS id,
		SUM(p.camp_id = '.$_SESSION['camp']['id'].') AS visible,
		p.camp_id,
		p.name AS product,
		g.label AS gender,
		s.label AS size,
		(SELECT SUM(st.items) FROM stock AS st, products AS p2, locations AS l WHERE st.product_id = p2.id AND p2.gender_id = g.id AND st.size_id = s.id AND st.location_id = l.id AND NOT st.deleted AND l.visible AND p.name = p2.name AND l.camp_id = '.$_SESSION['camp']['id'].') AS stock,
		COALESCE( NULLIF( (SELECT COUNT(st.id) FROM stock AS st, products AS p2, locations AS l WHERE st.product_id = p2.id AND p2.gender_id = g.id AND st.size_id = s.id AND st.location_id = l.id AND NOT st.deleted AND l.visible AND p.name = p2.name AND l.camp_id = '.$_SESSION['camp']['id'].'),0),"") AS boxes,
		ROUND((SELECT COUNT(id) FROM people AS p2 WHERE p2.visible AND NOT p2.deleted AND p2.camp_id = '.$_SESSION['camp']['id'].' AND  
		(IF(g.male,p2.gender="M",0) OR IF(g.female,p2.gender="F",0)) AND 
		(IF(g.adult,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p2.date_of_birth)), "%Y")+0>=13,0) OR IF(g.baby,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p2.date_of_birth)), "%Y")+0<2,0) OR IF(g.child,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p2.date_of_birth)), "%Y")+0 BETWEEN 2 AND 13,0)))*IFNULL(s.portion,100)/100) AS target,
		p.amountneeded,';

	foreach($camps as $key=>$camp) {
		$insert[] = '(SELECT SUM(items) FROM stock AS st, locations AS l, products AS p2 WHERE st.product_id = p2.id AND st.location_id = l.id AND l.visible AND NOT st.deleted AND p.name = p2.name AND p2.gender_id = g.id AND st.size_id = s.id AND l.camp_id = '.$key.') AS "camp'.$key.'"';
	} 
	$query .= join(',',$insert);

	$query .= 'FROM sizegroup AS sg, products AS p,	genders AS g, sizes AS s
		WHERE p.gender_id = g.id AND p.sizegroup_id = sg.id AND s.sizegroup_id = sg.id AND NOT p.deleted AND p.visible
		GROUP BY p.name, g.id, s.label
		ORDER BY p.name, g.seq, s.seq';
	
	$data = getlistdata($query);

	foreach($data as $key=>$d) {
		$data[$key]['result'] = ($d['stock']/$d['target']*$d['amountneeded']/10);
		if(!$data[$key]['target']) $data[$key]['target'] = '';
	}

	listsetting('allowcopy', false);
	listsetting('allowadd', false);
	listsetting('allowdelete', false);
	listsetting('allowselect', false);
	listsetting('allowselectall', false);
	listsetting('allowsort', true);

	addcolumn('text','Product','product');
	addcolumn('text','Gender','gender');
	addcolumn('text','Size','size');
	addcolumn('text','Items','stock');
	addcolumn('text','Boxes','boxes');
	if(db_value('SELECT market FROM camps WHERE id = :camp', array('camp'=>$_SESSION['camp']['id']))) {
		addcolumn('text','People','target');
#		addcolumn('text','','result');
		addcolumn('bar','Score','result2');
	}
	foreach($camps as $key=>$camp) {
		addcolumn('text',$camp,'camp'.$key);
	}
	
	$cmsmain->assign('data',$data);
	$cmsmain->assign('listconfig',$listconfig);
	$cmsmain->assign('listdata',$listdata);
	$cmsmain->assign('include','cms_list.tpl');

