<?php

	//main MYSQL table
	$table='stock';
	//PHP script	
	$action = 'need';
	//ajax for period selection
	$ajax = checkajax();
	if(!DEFINED('CORE')) include('core.php');
	//array of all camps
 	$camps = db_simplearray('SELECT id, name FROM camps WHERE id != :camp_id',array('camp_id'=>$_SESSION['camp']['id']));
	
	//addfield('custom','','<div class="noprint tipofday"><h3> What do we need the next weeks?</h3><p>You will see an estimate of the clothes needed the next weeks based on the "Estimated Annual Need (EAN)". You can change the EAN in the "Products" menu. Negative effective Need means we have to much.<p><div>');

	if (!$ajax) {
		initlist();

		//settings
		listsetting('allowcopy', false);
		listsetting('allowadd', false);
		listsetting('allowdelete', false);
		listsetting('allowselect', false);
		listsetting('allowselectall', false);
		listsetting('allowsort', true);
		listsetting('search', array('p.name', 'g.label', 's.label'));
		//click on row to get to edit the product's ean
		listsetting('edit','products_edit');

		//columns
		addcolumn('text','Product','product');
		addcolumn('text','Gender','gender');
		addcolumn('text','Size','size');
		addcolumn('text','Annual Need','ean_print');
		addcolumn('text','People','target');
		addcolumn('text','Stock','stock');
		addcolumn('text','Boxes','boxes');
		addcolumn('text','eff. Need','diff_max');
		//Stock of all camps
		foreach($camps as $key=>$camp) {
			addcolumn('text',$camp,'camp_'.$key);
		}

		//MYSQL query for DEFAULT period
		$weeks = db_row('SELECT week_min AS min, week_max AS max FROM need_periods WHERE id=1');

		//MYSQL query
		$query = '
		SELECT 
			CONCAT(pro.id,"-",g.id,"-",IFNULL(s.id,"")) AS id,
			pro.name AS product,
			g.label AS gender,
			s.label AS size,
			(SELECT COALESCE(SUM(st.items),0)
				FROM stock AS st, products AS pro2, locations AS l
				WHERE st.product_id = pro2.id AND pro2.gender_id = g.id AND st.size_id = s.id AND st.location_id = l.id AND NOT st.deleted AND l.visible AND pro.name = pro2.name AND l.camp_id = '.$_SESSION['camp']['id'].') AS stock,
			(SELECT COALESCE(COUNT(st.id),0)
				FROM stock AS st, products AS pro2, locations AS l
				WHERE st.product_id = pro2.id AND pro2.gender_id = g.id AND st.size_id = s.id AND st.location_id = l.id AND NOT st.deleted AND l.visible AND pro.name = pro2.name AND l.camp_id = '.$_SESSION['camp']['id'].') AS boxes,
			ROUND((SELECT COUNT(id) FROM people AS peo WHERE NOT peo.deleted AND peo.camp_id = '.$_SESSION['camp']['id'].' AND  
			(IF(g.male,peo.gender="M",0) OR IF(g.female,peo.gender="F",0)) AND 
			(IF(g.adult,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), peo.date_of_birth)), "%Y")+0>=13,0) OR IF(g.baby,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), peo.date_of_birth)), "%Y")+0<2,0) OR IF(g.child,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), peo.date_of_birth)), "%Y")+0 BETWEEN 2 AND 13,0)))*IFNULL(s.portion,100)/100) AS target,
			ROUND(pro.amountneeded,1) AS ean_print,
			pro.amountneeded AS ean';

		foreach($camps as $key=>$camp) {
			$query .= ',
			(SELECT IF(pro.name = pro2.name, COALESCE(SUM(st.items),0), NULL)
				FROM stock AS st, products AS pro2, locations AS l
				WHERE st.product_id = pro2.id AND pro2.gender_id = g.id AND st.size_id = s.id AND st.location_id = l.id AND NOT st.deleted AND l.visible AND pro.name = pro2.name AND l.camp_id = '.$key.') AS camp_'.$key ;
		} 

		$query .= '
		FROM 
			sizegroup AS sg,
			products AS pro,
			genders AS g,
			sizes AS s
		WHERE
			pro.camp_id = '.$_SESSION['camp']['id'].' AND
			pro.gender_id = g.id AND
			pro.sizegroup_id = sg.id AND
			s.sizegroup_id = sg.id AND
			NOT pro.deleted
		GROUP BY 
			pro.name, g.id, s.label
		ORDER BY 
			pro.name, g.seq, s.seq';
		
		$data = getlistdata($query);

		//Calucaltion of Need
		foreach($data as $key=>$d) {
			$data[$key]['diff_min'] = round(($d['target']*$d['ean']*$weeks["min"]/52)-$d['stock']);
			$data[$key]['diff_max'] = round(($d['target']*$d['ean']*$weeks["max"]/52)-$d['stock']);
			if (!$data[$key]['target']) $data[$key]['target'] = '0';
		}

		//move data to Zmarty object
		$cmsmain->assign('title','Needed items');
		$cmsmain->assign('data',$data);
		$cmsmain->assign('listconfig',$listconfig);
		$cmsmain->assign('listdata',$listdata);
		$cmsmain->assign('include','cms_list.tpl');
	}
