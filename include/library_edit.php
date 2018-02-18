<?php

	$table = 'library';
	$action = 'library_edit';

	if($_POST) {

		$handler = new formHandler($table);

		$savekeys = array('booktitle_en','booktitle_en', 'author');
		$id = $handler->savePost($savekeys);

		redirect('?action='.$_POST['_origin']);
	}

	$data = db_row('SELECT * FROM '.$table.' WHERE id = :id',array('id'=>$id));

	// open the template
	$cmsmain->assign('include','cms_form.tpl');
	addfield('hidden','','id');

	// put a title above the form
	$cmsmain->assign('title','Book');

	addfield('text','Book title (en)','booktitle_en');
	addfield('text','Book title (ar)','booktitle_ar');
	
// 	addfield('select', 'Category', 'category_id', array('required'=>true, 'width'=>3, 'multiple'=>false, 'query'=>'SELECT id AS value, label FROM product_categories ORDER BY seq'));

	addfield('line','','',array('aside'=>true));
	addfield('created','Created','created',array('aside'=>true));

	// place the form elements and data in the template
	$cmsmain->assign('data',$data);
	$cmsmain->assign('formelements',$formdata);
	$cmsmain->assign('formbuttons',$formbuttons);
