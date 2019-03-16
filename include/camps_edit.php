<?php

	$table = 'camps';
	$action = 'camps_edit';

	if($_POST) {


		$handler = new formHandler($table);

		$savekeys = array('name','market');
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
	$cmsmain->assign('title','Camp');

	addfield('text','Name','name',array('setformtitle'=>true));
	addfield('line');
	addfield('checkbox','Has a market','market',array('onchange'=>"$('#area_marketsettings').toggle($(this).prop('checked'))"));
	
	addfield('areastart','market','marketsettings');


	addfield('areaend');
	

	// place the form elements and data in the template
	$cmsmain->assign('data',$data);
	$cmsmain->assign('formelements',$formdata);
	$cmsmain->assign('formbuttons',$formbuttons);
