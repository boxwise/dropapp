<?php
	$table = 'cms_functions';

	if($_POST) {

		$handler = new formHandler($table);
		#$handler->debug = true;
		$keys = array();
		$handler->savePost(array_merge($keys,array('title_en', 'include', 'parent_id', 'adminonly', 'visible', 'allusers', 'allcamps')));
		$handler->saveMultiple('camps','cms_functions_camps','cms_functions_id','camps_id');

		redirect('?action='.$_POST['_origin']);
	}

	// collect data for the form
	$data = db_row('SELECT * FROM '.$table.' WHERE id = :id',array('id'=>$id));

	// open the template
	$cmsmain->assign('include','cms_form.tpl');

	// put a title above the form
	$cmsmain->assign('title',$translate['cms_function']);

	// define tabs
	$title = (db_fieldexists('cms_functions','title_'.$lan)?'title_'.$lan:'title');

 	addfield('select','Onderdeel van','parent_id',array('required'=>true,'formatlist'=>'formatparent','multiple'=>false, 'placeholder'=>'Maak een keuze', 'options'=>getParentarray($table, 0, 1, $title)));

	addfield('text',$translate['cms_function'],'title_en',array('required'=>true));
	addfield('text',$translate['cms_function_include'],'include');

	addfield('checkbox','This item is visible in the menu','visible');
	addfield('checkbox','Only available for admin users','adminonly');
	addfield('checkbox','Available for all camps','allcamps');
	
	addfield('select','Available for these camps','camps',array('multiple'=>true,'query'=>'SELECT a.id AS value, a.name AS label, IF(x.cms_functions_id IS NOT NULL, 1,0) AS selected FROM camps AS a LEFT OUTER JOIN cms_functions_camps AS x ON a.id = x.camps_id AND x.cms_functions_id = '.intval($id).' ORDER BY seq'));

	addfield('created','Gemaakt','created',array('aside'=>true));

	$cmsmain->assign('data',$data);
	$cmsmain->assign('formelements',$formdata);
