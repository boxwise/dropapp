<?php
	$table = 'cms_functions';

	if($_POST) {

		$handler = new formHandler($table);
		#$handler->debug = true;
		$keys = array();
		$handler->savePost(array_merge($keys,array('title_en', 'include', 'parent_id', 'adminonly', 'visible', 'allusers', 'allcamps','fororgansations')));

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
	addfield('checkbox','Organisation wide functions (shown if no camp is selected)','fororgansations');
	
	addfield('created','Gemaakt','created',array('aside'=>true));

	$cmsmain->assign('data',$data);
	$cmsmain->assign('formelements',$formdata);
