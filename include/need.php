<?php

	//main MYSQL table
	$table='stock';
	//PHP script	
	$action = 'need';
	//ajax for period selection
	$ajax = checkajax();

	//MYSQL query for DEFAULT period
	//array of all camps
 	$camps = db_simplearray('SELECT id, name FROM camps WHERE id != :camp_id',array('camp_id'=>$_SESSION['camp']['id']));
	$market = db_value('SELECT market FROM camps WHERE id = :camp', array('camp'=>$_SESSION['camp']['id']));
	
	if($_GET['resetfilter2']) {
		$_SESSION['filter2']['need'] = 1;
		$_GET['resetfilter2'] = false;
	}
	if(!isset($_SESSION['filter2']['need'])) {
		$_SESSION['filter2']['need'] = 1;
		$_GET['filter2'] = 1;
	}
	
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
		listsetting('search', array('pro.name', 'g.label', 's.label'));
		listsetting('manualquery',true);
		//click on row to get to edit the product's ean
		//listsetting('allowedit', false);
		listsetting('edit','stock-list_edit');

		//columns
		addcolumn('text','Product','product');
		addcolumn('text','Gender','gender');
		addcolumn('text','Size','size');
		if($market) {
			addcolumn('text','People','target');
			addcolumn('html','Annual Need','ean_print');
		}
		addcolumn('text','Stock','stock');
		addcolumn('text','Boxes','boxes');
		if($market) {
			addcolumn('text','Color', 'target');
			addcolumn('need','Under/overstock','diff_max');
		}
		foreach($camps as $key=>$camp) {
			addcolumn('text',$camp,'camp_'.$key);
		}

		//Filter
 		listfilter(array('label'=>'Both needed and overstock','options'=>array('green'=>'Good stock', 'red'=>'Needed', 'blue' =>'Overstock'),'filter'=>'color'));
 		
 		listfilter2(array('label'=>'Scope','query'=>'SELECT id, label FROM need_periods ORDER BY week_min','filter'=>'scope'));


		//MYSQL query for products of the own camp
		$query = '
		SELECT 
			CONCAT(pro.id,"-",g.id,"-",IFNULL(s.id,"")) AS id,
			1 AS visible, 
			pro.name AS product,
			g.label AS gender,
			s.label AS size,
			ROUND(pro.amountneeded,1) AS ean_print,
			pro.amountneeded AS ean,
			COALESCE(ROUND((SELECT COUNT(id) FROM people AS peo WHERE NOT peo.deleted AND peo.camp_id = '.$_SESSION['camp']['id'].' AND  
			(IF(g.male,peo.gender="M",0) OR IF(g.female,peo.gender="F",0)) AND 
			(IF(g.adult,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), peo.date_of_birth)), "%Y")+0>=13,0) OR IF(g.baby,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), peo.date_of_birth)), "%Y")+0<2,0) OR IF(g.child,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), peo.date_of_birth)), "%Y")+0 BETWEEN 2 AND 13,0)))*IFNULL(s.portion,100)/100),0) AS target,
			COALESCE(SUM(st.items),0) AS stock,
			COALESCE(COUNT(st.items),0) AS boxes';
			
		foreach($camps as $key=>$camp) {
			$query .= ', st_'.$key.'.calc AS camp_'.$key;
		}
		
		$query .= '
		FROM 	
			(sizegroup AS sg,
			genders AS g,
			sizes AS s,
			products AS pro)
		LEFT JOIN 
			(SELECT st.items, st.product_id, st.size_id, s.label
				FROM stock AS st, locations AS l, sizes AS s
				WHERE NOT st.deleted AND st.location_id = l.id AND l.visible AND st.size_id = s.id) st  
			ON (st.product_id = pro.id AND UPPER(st.label) = UPPER(s.label))';
		//left joins for other camps to distinguish between NULL and 0
		foreach($camps as $key=>$camp) {
			$query .= '
		LEFT JOIN
			(SELECT COALESCE(SUM(st.items),0) AS calc, UPPER(pro.name) AS product, pro.gender_id, UPPER(s.label) AS size
				FROM (products AS pro, sizes AS s, locations AS l)
				LEFT JOIN stock st
				ON (NOT st.deleted AND st.location_id = l.id AND pro.id = st.product_id AND s.id = st.size_id)
				WHERE l.visible AND l.camp_id = '.$key.' AND pro.camp_id = '.$key.'
				GROUP BY UPPER(pro.name), pro.gender_id, UPPER(s.label)) st_'.$key.'
			ON (st_'.$key.'.product = UPPER(pro.name) AND st_'.$key.'.gender_id = g.id AND st_'.$key.'.size = UPPER(s.label))';
		}
		$query .='
		WHERE
			NOT pro.deleted AND
			pro.camp_id = '.$_SESSION['camp']['id'].' AND
			pro.gender_id = g.id AND
			pro.sizegroup_id = sg.id AND
			s.sizegroup_id = sg.id';

		switch($_SESSION['filter']['need']) {
			case 'red':
				$query .= ' ';
				break;
			case 'green':
				$query .= ' ';
				break;
			case 'blue':
				$query .= ' ';
				break;
		}

		($listconfig['searchvalue']?$query .=' AND (pro.name LIKE "%'.$listconfig['searchvalue'].'%" OR g.label LIKE "%'.$listconfig['searchvalue'].'%" OR s.label LIKE "%'.$listconfig['searchvalue'].'%")':'').

		$query .= ' GROUP BY 
			pro.name, g.id, s.label
		ORDER BY 
			pro.name, g.seq, s.seq';
		
		//get visible data from camp
		$data = getlistdata($query);
		
		foreach($data as $key=>$d) {
			//href for ean_print to easily change "estimated annual need"
			$data[$key]['ean_print'] = '<a href="?action=products_edit&origin='.$action.'&id='.$data[$key]['id'].'" class="tooltip-this" title="Click here to edit the estimated annual need">'.$d['ean_print'].'</a>';

			$weeks = db_row('SELECT week_min AS min, week_max AS max FROM need_periods WHERE id = :id',array('id'=>$_SESSION['filter2']['need']));
			
			//calculation of need for the time periods 
			$data[$key]['diff_min'] = round(($d['target']*$d['ean']*$weeks["min"]/52)-$d['stock']);
			$data[$key]['diff_max'] = round(($d['target']*$d['ean']*$weeks["max"]/52)-$d['stock']);
			//decision if items should be ordered or dropped
			if($data[$key]['diff_min'] >= 0) {$data[$key]['color'] = "red";}
			else if($data[$key]['diff_max'] < 0) {$data[$key]['color'] = "blue";} 
			else {
				$data[$key]['color'] = "green";
				$data[$key]['diff_max'] = "";
			}			
		}

		//products of other camps hidden
		if(false){ 
			//MYSQL query to show the products of other camps
			$query_other = '
			SELECT 
				CONCAT(pro.id,"-",g.id,"-",IFNULL(s.id,"")) AS id,
				0 AS visible, 
				pro.name AS product,
				g.label AS gender,
				s.label AS size';
			foreach($camps as $key=>$camp) {
				$query_other .= ', st_'.$key.'.calc AS camp_'.$key;
			}
			$query_other .= '
			FROM 	
				(sizegroup AS sg,
				genders AS g,
				sizes AS s,
				products AS pro)';
			//left joins for other camps to distinguish between NULL and 0
			foreach($camps as $key=>$camp) {
				$query_other .= '
			LEFT JOIN
				(SELECT COALESCE(SUM(st.items),0) AS calc, UPPER(pro.name) AS product, pro.gender_id, UPPER(s.label) AS size
					FROM (products AS pro, sizes AS s, locations AS l)
					LEFT JOIN stock st
					ON (NOT st.deleted AND st.location_id = l.id AND pro.id = st.product_id AND s.id = st.size_id)
					WHERE l.visible AND l.camp_id = '.$key.' AND pro.camp_id = '.$key.'
					GROUP BY UPPER(pro.name), pro.gender_id, UPPER(s.label)) st_'.$key.'
				ON (st_'.$key.'.product = UPPER(pro.name) AND st_'.$key.'.gender_id = g.id AND st_'.$key.'.size = UPPER(s.label))';
			}
			$query_other .='
			WHERE
				NOT pro.deleted AND
				NOT pro.camp_id = '.$_SESSION['camp']['id'].' AND
				pro.gender_id = g.id AND
				pro.sizegroup_id = sg.id AND
				s.sizegroup_id = sg.id
			GROUP BY 
				pro.name, g.id, s.label
			ORDER BY 
				pro.name, g.seq, s.seq';			
			$data_other = getlistdata($query_other);
			
			//Merge and sort the two data arrays
			$data = array_merge($data,$data_other);

		}
	
		//move data to Zmarty object
		$cmsmain->assign('title','Needed items');
		$cmsmain->assign('data',$data);
		$cmsmain->assign('listconfig',$listconfig);
		$cmsmain->assign('listdata',$listdata);
		$cmsmain->assign('include','cms_list.tpl');
	} else {
		//ajax for select of time periods
	}

