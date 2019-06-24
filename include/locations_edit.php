<?php

	$table = 'locations';
	$action = 'locations_edit';

	if($_POST) {

		verify_campaccess_location($_POST['id']);
		if(!in_array($_POST['camp_id'],camplist(true))) trigger_error("You don't have access to this record");
	
		$handler = new formHandler($table);

		$savekeys = array('label', 'visible', 'camp_id', 'container_stock');
		$id = $handler->savePost($savekeys);

		redirect('?action='.$_POST['_origin']);
	}

	$data = db_row('SELECT * FROM '.$table.' WHERE id = :id',array('id'=>$id));

	#verify_campaccess_location($id);
	
	if (!$id) {
		$data['visible'] = 1;
		$data['camp_id'] = $_SESSION['camp']['id'];
	}

	// open the template
	$cmsmain->assign('include','cms_form.tpl');

	// put a title above the form
	$cmsmain->assign('title','Location');

	addfield('hidden','','id');
	addfield('hidden','','camp_id');
	addfield('text','Label','label');
	addfield('checkbox','Stockroom','container_stock');


	addfield('checkbox','Visible','visible',array('aside'=>true));
	addfield('line','','',array('aside'=>true));
	addfield('created','Created','created',array('aside'=>true));


	// place the form elements and data in the template
	$cmsmain->assign('data',$data);
	$cmsmain->assign('formelements',$formdata);
	$cmsmain->assign('formbuttons',$formbuttons);
