<?php

	$table = 'food';
	$action = 'food_edit';

	if($_POST) {

		$handler = new formHandler($table);

		$savekeys = array('name', 'package', 'unit_id', 'peradult', 'perchild','price', 'stock');
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
	$cmsmain->assign('title','Food product');

	addfield('text','Product','name');

	addfield('line','','');

	addfield('number','Package size','package',array('width'=>2));
	addfield('number','Per adult per cycle','peradult',array('width'=>2));
	addfield('number','Per child per cycle','perchild',array('width'=>2));
	addfield('select', 'Unit', 'unit_id', array('width'=>2, 'multiple'=>false, 'query'=>'SELECT longlabel AS label, id AS value FROM units ORDER BY seq'));

	addfield('number','Price per package','price',array('width'=>2));
	addfield('number','Units in stock','stock',array('width'=>2));

	addfield('line','','',array('aside'=>true));
	addfield('created','Created','created',array('aside'=>true));

	// place the form elements and data in the template
	$cmsmain->assign('data',$data);
	$cmsmain->assign('formelements',$formdata);
	$cmsmain->assign('formbuttons',$formbuttons);
