<?php

	$table = 'camps';
	$action = 'camps_edit';

	if($_POST) {

		$handler = new formHandler($table);

		$savekeys = array('name','market','familyidentifier','delete_inactive_users','food','bicycle','idcard','workshop','laundry','schedulestart','schedulestop','schedulebreak','schedulebreakstart','schedulebreakduration','scheduletimeslot','dropsperadult','dropsperchild','dropcapadult','dropcapchild','bicyclerenttime','adult_age','daystokeepdeletedpersons','extraportion','maxfooddrops_adult','maxfooddrops_child','bicycle_closingtime','bicycle_closingtime_saturday','organisation_id');
		$id = $handler->savePost($savekeys);
		$handler->saveMultiple('functions','cms_functions_camps','camps_id','cms_functions_id');

		redirect('?action='.$_POST['_origin']);
	}

	$data = db_row('SELECT * FROM '.$table.' WHERE id = :id',array('id'=>$id));

	if (!$id) {
		$data['visible'] = 1;
		$data['organisation_id'] = $_SESSION['organisation']['id'];
	}

	// open the template
	$cmsmain->assign('include','cms_form.tpl');
	addfield('hidden','','id');
	addfield('hidden','','organisation_id');

	// put a title above the form
	$cmsmain->assign('title','Camp');


	
	
	$tabs['general']='General Settings';
	$tabs['features']='Your Features';
	$tabs['market'] = 'Free Shop'; 
	$tabs['bicycle'] = 'Rent Bicycles';
	$tabs['food'] = 'Food Distribution';
	
	$cmsmain->assign('tabs',$tabs);

	addfield('text','Name','name',array('setformtitle'=>true, 'tab'=>'general'));
	addfield('number', 'Delete inactive residents after ... Days', 'delete_inactive_users', array('tab'=>'general'));
	addfield('number', 'Days to keep deleted persons', 'daystokeepdeletedpersons', array('tab'=>'general'));
	addfield('number', 'Adult Age', 'adult_age', array('tab'=>'general'));
	addfield('text', 'Location identifier of residents', 'familyidentifier', array('tab'=>'general'));
	addfield('checkbox', 'Do you give out general ID-cards?', 'idcard', array('tab'=>'general'));

	addfield('select','Functions available for this camp','functions',array('tab'=>'features','multiple'=>true,'query'=>'
		SELECT a.id AS value, a.title_en AS label, IF(x.camps_id IS NOT NULL, 1,0) AS selected 
		FROM cms_functions AS a 
		LEFT OUTER JOIN cms_functions_camps AS x ON a.id = x.cms_functions_id AND x.camps_id = '.intval($id).' 
		WHERE a.parent_id != 0 AND a.visible AND NOT a.allcamps AND NOT a.fororganisations AND NOT a.adminonly AND NOT a.allusers
		ORDER BY seq'));
	addfield('checkbox', 'Running a Free Shop?', 'market', array('tab'=>'features'));
	addfield('checkbox', 'Do you distribute food?', 'food', array('tab'=>'features'));
	addfield('checkbox', 'Do you rent bikes?', 'bicycle', array('tab'=>'features'));
	addfield('checkbox', 'Running a workshop?', 'workshop', array('tab'=>'features'));
	addfield('checkbox', 'Running a laundry?', 'laundry', array('tab'=>'features'));

	addfield('text', 'Default opening time', 'schedulestart', array('tab'=>'market'));
	addfield('text', 'Default closing time', 'schedulestop', array('tab'=>'market'));
	addfield('text', 'Is there usually a break?', 'schedulebreak', array('tab'=>'market'));
	addfield('text', 'Default start of break', 'schedulebreakstart', array('tab'=>'market'));
	addfield('text', 'Default break duration', 'schedulebreakduration', array('tab'=>'market'));
	addfield('text', 'Default duration of timeslots', 'scheduletimeslot', array('tab'=>'market')); 
	addfield('number', 'Default Drops per adult', 'dropsperadult', array('tab'=>'market'));
	addfield('number', 'Default Drops per child', 'dropsperchild', array('tab'=>'market'));
	addfield('number', 'Maximum Drops per adult', 'dropcapadult', array('tab'=>'market'));
	addfield('number', 'Maximum Drops per child', 'dropcapchild', array('tab'=>'market','width'=>2));

	addfield('number', 'Duration to rent a bicycle', 'bicyclerenttime', array('tab'=>'bicycle'));
	addfield('text', 'Default closing time', 'bicycle_closingtime', array('tab'=>'bicycle'));
	addfield('text', 'Default closing time on Saturday', 'bicycle_closingtime_saturday', array('tab'=>'bicycle'));

	addfield('checkbox', 'Do you give out extraportions?', 'extraportion', array('tab'=>'food'));
	addfield('number', 'Maximum Drops for food per adult', 'maxfooddrops_adult', array('tab'=>'food'));
	addfield('number', 'Maximum Drops for food per child', 'maxfooddrops_child', array('tab'=>'food'));

	// place the form elements and data in the template
	$cmsmain->assign('data',$data);
	$cmsmain->assign('formelements',$formdata);
	$cmsmain->assign('formbuttons',$formbuttons);
