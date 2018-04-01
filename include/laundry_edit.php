<?php

	$table = 'laundry_appointments';
	$action = 'laundry';
	
	if($_POST) {

		$_POST['cyclestart'] = $settings['laundry_cyclestart'];
		
		db_query('DELETE FROM laundry_appointments WHERE cyclestart = :cyclestart AND timeslot = :timeslot',array('cyclestart'=>$_POST['cyclestart'],'timeslot'=>$_POST['timeslot']));
		
		$handler = new formHandler($table);
		$savekeys = array('cyclestart', 'timeslot', 'people_id');
		$id = $handler->savePost($savekeys);

		redirect('?action=laundry'.$data['day']);
	}
	
	$timeslot = intval($_GET['timeslot']);

	$data = db_row('SELECT ls.id AS timeslot, ls.day, ls.time, la.people_id, ls.machine, p.* FROM laundry_slots AS ls LEFT OUTER JOIN laundry_appointments AS la ON la.timeslot = ls.id AND cyclestart = :cyclestart LEFT OUTER JOIN people AS p ON p.id = la.people_id WHERE timeslot = :id',array('cyclestart'=>$settings['laundry_cyclestart'], 'id'=>$timeslot));
	if(!$data) $data = db_row('SELECT ls.id AS timeslot, ls.day, ls.time, ls.machine FROM laundry_slots AS ls WHERE id = :id',array('id'=>$timeslot));
	
	if (!$id) {
		$data['visible'] = 1;
		$data['camp_id'] = $_SESSION['camp']['id'];
	}

	// open the template
	$cmsmain->assign('include','cms_form.tpl');

	// put a title above the form
	$cmsmain->assign('title', strftime('%A %d %B %Y', strtotime('+'.$data['day'].' days', strtotime($settings['laundry_cyclestart']))).'<br />'.db_value('SELECT label FROM laundry_times WHERE id = :time',array('time'=>$data['time'])).' '.db_value('SELECT label FROM laundry_machines WHERE id = :machine',array('machine'=>$data['machine'])));

	$data['timeslot'] = $timeslot;
	$data['day'] = $data['day'];
	addfield('hidden','','timeslot');
	addfield('hidden','','day');

	$people = db_array('SELECT p.id AS value, CONCAT(p.container, " ",p.firstname, " ", p.lastname) AS label, NOT visible AS disabled FROM people AS p WHERE parent_id = 0 AND NOT p.deleted AND camp_id = '.$_SESSION['camp']['id'].' GROUP BY p.id ORDER BY SUBSTRING(REPLACE(container,"PK","Z"),1,1), SUBSTRING(REPLACE(container,"PK","Z"), 2, 10)*1');
	array_unshift($people, array('value'=>-1,'label'=>'Drop Laundry','disabled'=>0));



	addfield('select','Find '.$_SESSION['camp']['familyidentifier'],'people_id',array('onchange'=>'updateLaundry("people_id")', 'multiple'=>false, 'options'=>$people));

		addfield('ajaxstart','', '', array('id'=>'ajax-content'));
		addfield('ajaxend');



	// place the form elements and data in the template
	$cmsmain->assign('data',$data);
	$cmsmain->assign('formelements',$formdata);
	$cmsmain->assign('formbuttons',$formbuttons);
