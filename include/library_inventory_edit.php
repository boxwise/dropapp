<?php

	$table = 'library';
	$action = 'library_edit';

	if($_POST) {

		$handler = new formHandler($table);

		$savekeys = array('booktitle_en','booktitle_en', 'author', 'code', 'publisher');
		$id = $handler->savePost($savekeys);

		redirect('?action='.$_POST['_origin']);
	}

	$data = db_row('SELECT * FROM '.$table.' WHERE id = :id',array('id'=>$id));

	// open the template
	$cmsmain->assign('include','cms_form.tpl');
	addfield('hidden','','id');

	// put a title above the form
	$cmsmain->assign('title',$data['booktitle_en']);

	addfield('text','Code','code',array('width'=>2));
	addfield('line');
	addfield('text','Book title (en)','booktitle_en',array('required'=>true,'setformtitle'=>true));
	addfield('text','Book title (ar)','booktitle_ar');
	addfield('text','Author','author');
	addfield('text','Publisher','publisher');
	addfield('line');
 	addfield('select', 'Type', 'type_id', array('width'=>3, 'multiple'=>false, 'query'=>'SELECT id AS value, label FROM library_type ORDER BY id'));
 	addfield('select', 'Level', 'level_id', array('width'=>3, 'multiple'=>false, 'query'=>'SELECT id AS value, label FROM library_level ORDER BY id'));

	addfield('line','','',array('aside'=>true));
	addfield('created','Created','created',array('aside'=>true));

	// place the form elements and data in the template
	$cmsmain->assign('data',$data);
	$cmsmain->assign('formelements',$formdata);
	$cmsmain->assign('formbuttons',$formbuttons);
