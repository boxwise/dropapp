<?php
	$table = stock;
	$ajax = checkajax();
	if(!DEFINED('CORE')) include('core.php');

	if(!$ajax) {

		$cmsmain->assign('title','Short Time Analysis');
		initlist();
		listsetting('inputontop', array('label' => array('min. time of storage', 'max. time of storage', 'calc based on last weeks'), 'default_val' => array(4, 12, 4), 'min' => 1));
		listsetting('haspagemenu', true);
		addpagemenu('need', 'NEEDED', array('link'=>'?action=short_time_ana', 'active'=>true));
		addpagemenu('drop', 'TOO MUCH', array('link'=>'?action=short_time_ana_drop'));
		listsetting('allowcopy', false);
		listsetting('allowadd', false);
		listsetting('allowdelete', false);
 		listsetting('allowsort', true);
		listsetting('allowselect', false);
		listsetting('allowselectall', false);

		addcolumn('text','Product','product');
		addcolumn('text','Gender','gender');
		addcolumn('text','Size','size');
		addcolumn('text','Given out','go');
#		addcolumn('text','Stock','stock');
#		addcolumn('text','BUY','buy');

		$query = '
		SELECT 
			CONCAT(p.id,"-",g.id,"-",IFNULL(s.id,"")) AS id,
			p.name AS product,
			g.label AS gender,
			s.label AS size,
			(SELECT SUM(
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
		


		$data= getlistdata('SELECT 

		$cmsmain->assign('listconfig',$listconfig);
		$cmsmain->assign('include','cms_list.tpl');
	}
