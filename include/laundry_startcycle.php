<?php


	if($_POST) {

		$settings['laundry_cyclestart'] = $_POST['cyclestart'];
		
		db_query('UPDATE settings SET value = :cyclestart WHERE code = "laundry_cyclestart"', array('cyclestart'=>strftime('%Y-%m-%d',strtotime($_POST['cyclestart']))));
		
		redirect('?action=laundry');
	}
	
	$data['cyclestart'] = strftime('%Y-%m-%d',strtotime('+14 days',strtotime($settings['laundry_cyclestart'])));
	
	// open the template
	$cmsmain->assign('include','cms_form.tpl');

	// put a title above the form
	$cmsmain->assign('title', 'Start new cycle');

	addfield('date','New cycle starts on','cyclestart',array('date'=>true, 'time'=>false));

	// place the form elements and data in the template
	$cmsmain->assign('data',$data);
	$cmsmain->assign('formelements',$formdata);
	$cmsmain->assign('formbuttons',$formbuttons);
