<?php

	$table = 'library';
	$action = 'library_edit';

	if($_POST) {

		$handler = new formHandler($table);

		$savekeys = array('booktitle_en','booktitle_ar', 'author', 'code', 'visible', 'camp_id');
		$id = $handler->savePost($savekeys);

		redirect('?action='.$_POST['_origin']);
	}

	$data = db_row('SELECT * FROM '.$table.' WHERE id = :id',array('id'=>$id));

	if(!$id) $data['camp_id'] = $_SESSION['camp']['id'];
	
	// open the template
	$cmsmain->assign('include','cms_form.tpl');
	addfield('hidden','','id');
	addfield('hidden','','camp_id');

	// put a title above the form
	$cmsmain->assign('title',$data['booktitle_en']);

	addfield('text','Code','code',array('width'=>2));
	addfield('line');
	addfield('text','English title','booktitle_en',array('setformtitle'=>true,'required'=>true));
	addfield('text','Original title','booktitle_ar');
	addfield('text','Author','author');
	addfield('line');
 	addfield('select', 'Type', 'type_id', array('width'=>3, 'multiple'=>false, 'query'=>'SELECT id AS value, label FROM library_type WHERE camp_id = '.intval($_SESSION['camp']['id']).' ORDER BY id'));
	addfield('checkbox', 'Available for borrowing', 'visible');

	addfield('line','','',array('aside'=>true));
	addfield('created','Created','created',array('aside'=>true));

	// place the form elements and data in the template
	$cmsmain->assign('data',$data);
	$cmsmain->assign('formelements',$formdata);
	$cmsmain->assign('formbuttons',$formbuttons);
