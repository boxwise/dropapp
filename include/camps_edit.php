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
		$data = db_defaults('camps');
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
	$tabs['market'] = 'Free Shop'; 
	$tabs['bicycle'] = 'Rent Bicycles';
	$tabs['food'] = 'Food Distribution';
	
	$cmsmain->assign('tabs',$tabs);

	addfield('text','Camp name','name',array('setformtitle'=>true, 'tab'=>'general'));
	addfield('line','','',array('tab'=>'general'));

	addfield('select','Functions available for this camp','functions',array('tab'=>'general','multiple'=>true,'query'=>'SELECT a.id AS value, a.title_en AS label, IF(x.camps_id IS NOT NULL, 1,0) AS selected FROM cms_functions AS a LEFT OUTER JOIN cms_functions_camps AS x ON a.id = x.cms_functions_id AND x.camps_id = '.intval($id).' WHERE a.parent_id != 0 AND a.visible AND NOT a.allcamps ORDER BY seq'));
	addfield('line','','',array('tab'=>'general'));

	addfield('number', 'Delete inactive residents', 'delete_inactive_users', array('tab'=>'general','width'=>2,'tooltip'=>'Residents without activity in Boxwise will be deleted. Deleted residents will remain visible in the Deleted tab in the Residents page.'));
	addfield('number', 'Days to keep deleted persons', 'daystokeepdeletedpersons', array('tab'=>'general','width'=>2,'tooltip'=>'Deleted residents will remain visible in the Deleted tab in the Residents page and will be completely deleted after a while. Here you can define how long they will remain in the Deleted list.'));
	addfield('line','','',array('tab'=>'general'));

	addfield('number', 'Adult age', 'adult_age', array('tab'=>'general','width'=>2,'tooltip'=>'For some functions we distinct between children and adults. Fill in here the lowest age considered adult for this camp.'));
	addfield('line','','',array('tab'=>'general'));

	addfield('text', 'Location identifier of residents', 'familyidentifier', array('tab'=>'general','tooltip'=>'Generally this refers to the kind of housing that people have: tent, container, house or something else.'));
	addfield('line','','',array('tab'=>'general'));

	addfield('checkbox', 'Do you give out general ID-cards?', 'idcard', array('tab'=>'general'));
	addfield('line','','',array('tab'=>'general'));

	addfield('checkbox', 'This camp has a Free Shop?', 'market', array('tab'=>'general'));
	addfield('checkbox', 'This camp has a food distribution programme in the Free Shop', 'food', array('tab'=>'general'));
	addfield('checkbox', 'This camp runs a Bicycle/tools borrowing program', 'bicycle', array('tab'=>'general'));
	addfield('checkbox', 'This camp runs a workshop for residents', 'workshop', array('tab'=>'general'));
	addfield('checkbox', 'This camp has a laundry station for residents', 'laundry', array('tab'=>'general'));

	addfield('title','Drops per cycle','',array('tab'=>'market'));
	addfield('number', 'Drops per adult', 'dropsperadult', array('tab'=>'market','width'=>2));
	addfield('number', 'Drops per child', 'dropsperchild', array('tab'=>'market','width'=>2));
	addfield('line','','',array('tab'=>'market'));
	addfield('title','Drop capping','',array('tab'=>'market'));
	addfield('number', 'Maximum Drops per adult', 'dropcapadult', array('tab'=>'market','width'=>2));
	addfield('number', 'Maximum Drops per child', 'dropcapchild', array('tab'=>'market','width'=>2));
	addfield('line','','',array('tab'=>'market'));
	addfield('title','Schedule','',array('tab'=>'market'));
	addfield('date', 'Daily start time', 'schedulestart', array('tab'=>'market','date'=>false,'time'=>true));
	addfield('date', 'Daily end', 'schedulestop', array('tab'=>'market','date'=>false,'time'=>true));
	addfield('select','Length of timeslots','scheduletimeslot',array('tab'=>'market','multiple'=>false,'options'=>array(
			array('value'=> '3', 'label'=>'3 hours'),
			array('value'=> '2', 'label'=>'2 hours'),
			array('value'=> '1', 'label'=>'1 hour'),
			array('value'=> '0.5', 'label'=>'30 minutes'), 
			array('value'=> '0.25', 'label'=>'15 minutes')
			), 'required'=> true));
	addfield('line','','',array('tab'=>'market'));
	addfield('checkbox', 'Include lunch break?', 'schedulebreak', array('tab'=>'market'));
	addfield('date', 'Lunch time', 'schedulebreakstart', array('tab'=>'market','date'=>false,'time'=>true,));
	addfield('select','Lunch duration','schedulebreakduration',array('tab'=>'market','multiple'=>false, 'options'=>array(
			array('value'=> '0.5', 'label'=>'30 minutes'), 
			array('value'=> '1', 'label'=>'1 hour'),
			array('value'=> '1.5', 'label'=>'1,5 hour'),
			array('value'=> '2', 'label'=>'2 hours')
			), 'required'=> true));


	addfield('number', 'Max duration to rent a bicycle', 'bicyclerenttime', array('tab'=>'bicycle'));
	addfield('date', 'Closing time', 'bicycle_closingtime', array('tab'=>'bicycle','date'=>false,'time'=>true));
	addfield('date', 'Closing time on Saturday', 'bicycle_closingtime_saturday', array('tab'=>'bicycle','date'=>false,'time'=>true));

	addfield('checkbox', 'Do you give out extraportions?', 'extraportion', array('tab'=>'food'));
	addfield('number', 'Maximum Drops for food per adult', 'maxfooddrops_adult', array('tab'=>'food'));
	addfield('number', 'Maximum Drops for food per child', 'maxfooddrops_child', array('tab'=>'food'));

	// place the form elements and data in the template
	$cmsmain->assign('data',$data);
	$cmsmain->assign('formelements',$formdata);
	$cmsmain->assign('formbuttons',$formbuttons);
