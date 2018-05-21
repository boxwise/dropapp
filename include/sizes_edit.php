<?php

	$table = 'sizes';
	$action = 'sizes_edit';

	if($_POST) {


		$handler = new formHandler($table);

		$savekeys = array('label','sizegroup_id','portion');
		$id = $handler->savePost($savekeys);

		redirect('?action='.$_POST['_origin']);
	}

	$data = db_row('SELECT * FROM '.$table.' WHERE id = :id',array('id'=>$id));

	if (!$id) {
		$data['visible'] = 1;
	}

	// open the template
	$cmsmain->assign('include','cms_form.tpl');
	addfield('hidden','','id');

	// put a title above the form
	$cmsmain->assign('title','Size');

	addfield('text','Label','label');
	addfield('select', 'Size group', 'sizegroup_id', array('required'=>false, 'multiple'=>false, 'query'=>'SELECT id AS value, label FROM sizegroup ORDER BY seq'));
	addfield('number','Portion','portion',array('tooltip'=>'Fill in the percentage that this size takes in this size group'));


	// place the form elements and data in the template
	$cmsmain->assign('data',$data);
	$cmsmain->assign('formelements',$formdata);
	$cmsmain->assign('formbuttons',$formbuttons);
