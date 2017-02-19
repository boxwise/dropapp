<?php

	$table = 'stock';
	$action = 'stock-list';

	initlist();

	$cmsmain->assign('title','General stock');
	listsetting('search', array('p.name'));

	$locations = join(',',db_simplearray('SELECT id, id FROM locations WHERE visible AND camp_id = :camp_id',array('camp_id'=>$_SESSION['camp']['id'])));

	$data = getlistdata('
SELECT
	p.name AS product,
	g.label AS gender,
	s.label AS size,
	(SELECT SUM(items) FROM stock AS st, locations AS l WHERE st.size_id = s.id AND st.location_id = l.id AND NOT st.deleted AND l.visible AND st.product_id = p.id) AS stock,
	COALESCE( NULLIF( (SELECT COUNT(st.id) FROM stock AS st, locations AS l WHERE st.size_id = s.id AND st.location_id = l.id AND NOT st.deleted AND l.visible AND st.product_id = p.id),0),"") AS boxes, 

ROUND((SELECT COUNT(id) FROM people AS p2 WHERE visible AND NOT deleted AND 
(IF(g.male,p2.gender="M",0) OR IF(g.female,p2.gender="F",0)) AND 
(IF(g.adult,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p2.date_of_birth)), "%Y")+0>=13,0) OR IF(g.baby,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p2.date_of_birth)), "%Y")+0<2,0) OR IF(g.child,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p2.date_of_birth)), "%Y")+0 BETWEEN 2 AND 13,0)))*IFNULL(s.portion,100)/100) AS target,
	p.amountneeded

FROM
	sizegroup AS sg,
	sizes AS s,
	products AS p,
	genders AS g
WHERE
	p.gender_id = g.id AND
	p.sizegroup_id = sg.id AND
	s.sizegroup_id = sg.id AND
	p.camp_id = '.$_SESSION['camp']['id'].'
ORDER BY 
	p.name, g.label, s.label');

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
	if($_SESSION['camp']['market']) {
		addcolumn('text','People','target');
#		addcolumn('text','','result');
		addcolumn('bar','Score','result2');
	}
	
	$cmsmain->assign('data',$data);
	$cmsmain->assign('listconfig',$listconfig);
	$cmsmain->assign('listdata',$listdata);
	$cmsmain->assign('include','cms_list.tpl');

function array_orderby()
{
    $args = func_get_args();
    $data = array_shift($args);
    foreach ($args as $n => $field) {
        if (is_string($field)) {
            $tmp = array();
            foreach ($data as $key => $row)
                $tmp[$key] = $row[$field];
            $args[$n] = $tmp;
            }
    }
    $args[] = &$data;
    call_user_func_array('array_multisort', $args);
    return array_pop($args);
}
