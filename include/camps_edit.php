<?php

	$table = 'camps';
	$action = 'camps_edit';

	if($_POST) {


		$handler = new formHandler($table);

		$savekeys = array('name','market');
		$id = $handler->savePost($savekeys);
		$handler->saveMultiple('functions','cms_functions_camps','camps_id','cms_functions_id');

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
	addfield('checkbox','Has a market','market');
	
	addfield('select','Functions available for this camp','functions',array('multiple'=>true,'query'=>'SELECT a.id AS value, a.title_en AS label, IF(x.camps_id IS NOT NULL, 1,0) AS selected FROM cms_functions AS a LEFT OUTER JOIN cms_functions_camps AS x ON a.id = x.cms_functions_id AND x.camps_id = '.intval($id).' WHERE a.parent_id != 0 AND a.visible AND NOT a.allcamps ORDER BY seq'));
	

	// place the form elements and data in the template
	$cmsmain->assign('data',$data);
	$cmsmain->assign('formelements',$formdata);
	$cmsmain->assign('formbuttons',$formbuttons);
