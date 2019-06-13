<?php

	$table = 'cms_usergroups';
	$action = 'cms_usergroups_edit';

	if($_POST) {


		$handler = new formHandler($table);

		$savekeys = array('label');
		$id = $handler->savePost($savekeys);
		$handler->saveMultiple('camps', 'cms_usergroups_camps', 'cms_usergroups_id', 'camp_id');
		$handler->saveMultiple('cms_functions', 'cms_usergroups_functions', 'cms_usergroups_id', 'cms_functions_id');

		redirect('?action='.$_POST['_origin']);
	}

	$data = db_row('SELECT * FROM '.$table.' WHERE id = :id',array('id'=>$id));

	if (!$id) {
		$data['visible'] = 1;
	}

	// open the template
	$cmsmain->assign('include','cms_form.tpl');

	// put a title above the form
	$cmsmain->assign('title','User group');

	addfield('text','Name','label');

	addfield('select','Available camps','camps',array('multiple'=>true,'query'=>'SELECT a.id AS value, a.name AS label, IF(x.cms_usergroups_id IS NOT NULL, 1,0) AS selected FROM camps AS a LEFT OUTER JOIN cms_usergroups_camps AS x ON a.id = x.camp_id AND x.cms_usergroups_id = '.intval($id).' ORDER BY seq'));

	addfield('select',$translate['cms_users_access'],'cms_functions',array('multiple'=>true,'query'=>'
	SELECT 
		a.id AS value, a.title_en AS label, IF(x.cms_usergroups_id IS NOT NULL, 1,0) AS selected 
	FROM cms_functions AS a 
	LEFT OUTER JOIN cms_usergroups_functions AS x ON a.id = x.cms_functions_id AND x.cms_usergroups_id = '.intval($id).' 
	WHERE NOT a.adminonly AND NOT a.allusers AND a.parent_id != 0 ORDER BY a.title_en, seq'));


	addfield('line','','',array('aside'=>true));
	addfield('created','Created','created',array('aside'=>true));


	// place the form elements and data in the template
	$cmsmain->assign('data',$data);
	$cmsmain->assign('formelements',$formdata);
	$cmsmain->assign('formbuttons',$formbuttons);
