<?php
	require_once('core.php');

	//main MYSQL table
	$table='stock';
	//PHP script	
	$action = 'need';

	//Page Title
	$cmsmain->assign('title','Needed items');

	if (!$_POST && !isset($_GET['resetsearch'])) {
		//Form to input the span for the estimation!
		//comment
		addfield('custom','','<div class="noprint tipofday"><h3> What do we need the next weeks?</h3><p>You will see an estimate of the clothes needed the next weeks based on the "Estimated Annual Need (EAN)". You can change the EAN in the "Products" menu. Negative effective Need means we have to much.<p><div>');
		//weeks Input
		addfield('number', 'Weeks', 'weeks', array('required'=>true,'width'=>2));
		$translate['cms_form_submit'] = 'Calculate Need';
		//move data to Zmarty object
		$cmsmain->assign('include','cms_form.tpl');
		$cmsmain->assign('translate',$translate);
		$cmsmain->assign('formelements',$formdata);
		$cmsmain->assign('data',$data);

	} else {
		if (isset($_POST['weeks'])) $_SESSION['weeks'] = $_POST['weeks'];

		//Stock List with Estimation of needed items
		initlist();
		listsetting('allowedit', false);
		listsetting('allowcopy', false);
		listsetting('allowadd', false);
		listsetting('allowdelete', false);
		listsetting('allowselect', false);
		listsetting('allowselectall', false);
		listsetting('allowsort', true);
		listsetting('search', array('p.name', 'g.label', 's.label'));

		//MYSQL query
		$query = '
		SELECT 
			CONCAT(p.id,"-",g.id,"-",IFNULL(s.id,"")) AS id,
			p.name AS product,
			g.label AS gender,
			s.label AS size,
			(SELECT COALESCE(SUM(st.items),0) FROM stock AS st, products AS p2, locations AS l WHERE st.product_id = p2.id AND p2.gender_id = g.id AND st.size_id = s.id AND st.location_id = l.id AND NOT st.deleted AND l.visible AND p.name = p2.name AND l.camp_id = '.$_SESSION['camp']['id'].') AS stock,
			ROUND((SELECT COUNT(id) FROM people AS p2 WHERE p2.visible AND NOT p2.deleted AND p2.camp_id = '.$_SESSION['camp']['id'].' AND  
			(IF(g.male,p2.gender="M",0) OR IF(g.female,p2.gender="F",0)) AND 
			(IF(g.adult,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p2.date_of_birth)), "%Y")+0>=13,0) OR IF(g.baby,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p2.date_of_birth)), "%Y")+0<2,0) OR IF(g.child,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p2.date_of_birth)), "%Y")+0 BETWEEN 2 AND 13,0)))*IFNULL(s.portion,100)/100) AS target,
			ROUND(p.amountneeded*'.$_SESSION['weeks'].'/52, 1) AS en,
			p.amountneeded AS ean
		FROM 
			sizegroup AS sg,
			products AS p,
			genders AS g,
			sizes AS s
		WHERE
			p.camp_id = '.$_SESSION['camp']['id'].' AND
			p.gender_id = g.id AND
			p.sizegroup_id = sg.id AND
			s.sizegroup_id = sg.id AND
			NOT p.deleted
		GROUP BY 
			p.name, g.id, s.label
		ORDER BY 
			p.name, g.seq, s.seq';
		
		$data = getlistdata($query);
		//$data['weeks']=$_SESSION['weeks']; 		//to debug

		//Calucaltion of Need
		foreach($data as $key=>$d) {
			$data[$key]['diff'] = round(($d['target']*$d['ean']*$_SESSION['weeks']/52)-$d['stock']);
			if (!$data[$key]['target']) $data[$key]['target'] = '0';
		}

		//columns
		addcolumn('text','Product','product');
		addcolumn('text','Gender','gender');
		addcolumn('text','Size','size');
		addcolumn('text','Need p.p.','en');
		addcolumn('text','People','target');
		addcolumn('text','Items','stock');
		addcolumn('text','eff. Need','diff');

		//move data to Zmarty object
		$cmsmain->assign('data',$data);
		$cmsmain->assign('listconfig',$listconfig);
		$cmsmain->assign('listdata',$listdata);
		$cmsmain->assign('include','cms_list.tpl');
	}
