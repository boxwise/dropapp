<?php

	$table = 'stock';
	$action = 'stock-list';

	initlist();

	$cmsmain->assign('title','General stock');
	listsetting('search', array('p.name'));

	$locations = join(',',db_simplearray('SELECT id, id FROM locations WHERE visible AND camp_id = :camp_id',array('camp_id'=>$_SESSION['camp']['id'])));


	$data = getlistdata('
SELECT CONCAT(p.id,"-",g.id,"-",IFNULL(s2.id,"")) AS id, p.id AS productid, p.name, g.id AS genderid, s2.id AS sizeid, g.label AS gender, s2.seq AS sizeseq, IFNULL(s2.label,"-") AS size, CONCAT(IFNULL(SUM(s.items),0)," (",COUNT(s.id),")") AS stock, s2.sizegroup_id,

	ROUND((SELECT
		COUNT(p2.id)
	FROM
		people AS p2
	WHERE
		(IF(g.male,gender="M",0) OR IF(g.female,gender="F",0)) AND
(IF(g.baby,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p2.date_of_birth)), "%Y")+0<2,0) OR IF(g.child,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p2.date_of_birth)), "%Y")+0>=2 AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p2.date_of_birth)), "%Y")+0<13,0) OR IF(g.adult,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p2.date_of_birth)), "%Y")+0>=13,0))
		AND
		p2.visible AND p2.camp_id = '.$_SESSION['camp']['id'].' AND
		NOT p2.deleted)* IFNULL(s2.portion,100)/100 )

	AS target,

	ROUND(IFNULL(SUM(s.items),0)/		((SELECT
		COUNT(p2.id)
	FROM
		people AS p2
	WHERE
		(IF(g.male,gender="M",0) OR IF(g.female,gender="F",0)) AND
(IF(g.baby,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p2.date_of_birth)), "%Y")+0<2,0) OR IF(g.child,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p2.date_of_birth)), "%Y")+0>=2 AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p2.date_of_birth)), "%Y")+0<13,0) OR IF(g.adult,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p2.date_of_birth)), "%Y")+0>=13,0))
		AND
		p2.visible AND p2.camp_id = '.$_SESSION['camp']['id'].' AND
		NOT p2.deleted)* IFNULL(s2.portion,100)/100 ) / p.amountneeded,1)

 AS result


FROM products AS p
LEFT OUTER JOIN genders AS g ON p.gender_id = g.id
LEFT OUTER JOIN stock AS s ON s.product_id = p.id AND NOT s.deleted
LEFT OUTER JOIN sizes AS s2 ON s.size_id = s2.id
LEFT OUTER JOIN locations AS l ON s.location_id = l.id AND l.id IN ('.$locations.')
WHERE NOT p.deleted 
GROUP BY p.name, g.label, s2.label
ORDER BY p.name, g.seq, s2.seq');

	foreach($data as $key=>$d) {
		$data[$key]['surplus'] = ($data[$key]['result']>0?$data[$key]['boxes']-intval($data[$key]['boxes']/$data[$key]['result']):'');
		if($data[$key]['surplus'] <= 0) $data[$key]['surplus'] = ' ';
		if($d['sizegroup_id']) {
			$result = db_query('SELECT * FROM sizes WHERE sizegroup_id = :sizegroup',array('sizegroup'=>$d['sizegroup_id']));
			while($row = db_fetch($result)) {
				if(!searchstock($d['name'],$d['gender'],$row['label'])) {
					$target = db_value('SELECT
	ROUND(COUNT(p2.id) * IFNULL(s.portion,100) / 100)
FROM
	people AS p2, genders AS g, sizes AS s
WHERE
	g.label = :gender AND s.id = :size AND p2.camp_id = :camp_id AND
	(IF(g.male,gender="M",0) OR IF(g.female,gender="F",0)) AND
	(IF(g.baby,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p2.date_of_birth)), "%Y")+0<2,0) OR IF(g.child,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p2.date_of_birth)), "%Y")+0>=2 AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p2.date_of_birth)), "%Y")+0<13,0) OR IF(g.adult,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p2.date_of_birth)), "%Y")+0>=13,0))
		AND
		p2.visible AND
		NOT p2.deleted',array('gender'=>$d['gender'],'size'=>$row['id'],'camp_id'=>$_SESSION['camp']['id']));
					$data[] = array('id'=>$d['productid'].'-'.$d['genderid'].'-'.$row['id'], 'name'=>$d['name'], 'gender'=>$d['gender'], 'sizeseq'=>$row['seq'], 'size'=>$row['label'], 'stock'=>0, 'sizegroup_id'=>$d['sizegroup_id'], 'target'=>$target, 'result'=>0, 'level'=>0);
				}
			}
		}
	}
	
	foreach($data as $key=>$d) {
		if(!$d['target']) $data[$key]['result'] = -1;
		$result = db_query('SELECT * FROM camps WHERE id != :id',array('id'=>$_SESSION['camp']['id']));
		while($row = db_fetch($result)) {
			$data[$key]['camp'.$row['id']] = db_value('SELECT SUM(items) FROM stock AS s, locations AS l, products AS p WHERE p.id = s.product_id AND s.location_id = l.id AND l.visible AND product_id = :productid AND p.gender_id = :genderid AND size_id = :sizeid AND l.camp_id = :campid',array('productid'=>$d['productid'],'genderid'=>$d['genderid'],'sizeid'=>$d['sizeid'],'campid'=>$row['id']));
		}
	}	

	$data = array_orderby($data, 'name', SORT_ASC, 'gender', SORT_ASC, 'sizeseq', SORT_ASC);


	function searchstock($name,$gender,$size) {
		global $data;
		foreach($data as $d) {
			if($d['name']==$name && $d['gender']==$gender && $d['size']==$size) return true;
		}
		return false;
	}
#
	listsetting('allowcopy', false);
	listsetting('allowadd', false);
	listsetting('allowdelete', false);
	listsetting('allowselect', false);
	listsetting('allowselectall', false);
	listsetting('allowsort', true);
	listsetting('width', 12);

	addcolumn('text','Product','name');
	addcolumn('text','Gender','gender');
	addcolumn('text','Size','size');
	addcolumn('text','Items (boxes)','stock');
	if($_SESSION['camp']['market']) {
		addcolumn('text','Surplus','surplus');
		addcolumn('text','People','target');
		#addcolumn('text','','result');
		addcolumn('bar','Score','result2');
	}
	
	$result = db_query('SELECT * FROM camps WHERE id != :id',array('id'=>$_SESSION['camp']['id']));
	while($row = db_fetch($result)) {
		addcolumn('text',$row['name'],'camp'.$row['id']);
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
