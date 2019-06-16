<?php

	$table = 'cms_usergroups';
	$action = 'cms_usergroups_edit';

	if($_SESSION['user']['is_admin'] || $_SESSION['usergroup']['userlevel'] > db_value('SELECT MIN(level) FROM cms_usergroups_levels')){

	if($_POST) {
		if($_SESSION['user']['is_admin'] || ($_SESSION['usergroup']['userlevel']> db_value('SELECT level FROM cms_usergroups_levels WHERE id = :id', array('id'=>$_POST['userlevel'])))) {

			$_POST['organisation_id'] = $_SESSION['organisation']['id'];

			$handler = new formHandler($table);

			$savekeys = array('label','allow_laundry_startcycle','allow_laundry_block','allow_borrow_adddelete','userlevel','organisation_id');
			$id = $handler->savePost($savekeys);
			$handler->saveMultiple('camps', 'cms_usergroups_camps', 'cms_usergroups_id', 'camp_id');
			$handler->saveMultiple('cms_functions', 'cms_usergroups_functions', 'cms_usergroups_id', 'cms_functions_id');

			redirect('?action='.$_POST['_origin']);
		} else {
			trigger_error("Naughty boy!");
		}
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

	addfield('select','Level','userlevel',array('required'=>true,'query'=>'
		SELECT id AS value, label 
		FROM cms_usergroups_levels 
		WHERE level < '.intval($_SESSION['usergroup']['userlevel']).' OR '.$_SESSION['user']['is_admin'].'
		ORDER BY level'));

	addfield('select','Available bases','camps',array('multiple'=>true,'query'=>'
		SELECT a.id AS value, a.name AS label, IF(x.cms_usergroups_id IS NOT NULL, 1,0) AS selected 
		FROM camps AS a 
		LEFT OUTER JOIN cms_usergroups_camps AS x ON a.id = x.camp_id AND x.cms_usergroups_id = '.intval($id).' 
		WHERE (NOT a.deleted OR a.deleted IS NULL) AND a.organisation_id = '.$_SESSION['organisation']['id'].'
		ORDER BY seq'));

	addfield('select',$translate['cms_users_access'],'cms_functions',array('multiple'=>true,'query'=>'
	SELECT 
		a.id AS value, a.title_en AS label, IF(x.cms_usergroups_id IS NOT NULL, 1,0) AS selected 
	FROM cms_functions AS a 
	LEFT OUTER JOIN cms_usergroups_functions AS x ON a.id = x.cms_functions_id AND x.cms_usergroups_id = '.intval($id).' 
	WHERE NOT a.adminonly AND NOT a.allusers AND a.parent_id != 0 AND a.visible
	ORDER BY a.title_en, seq'));

	addfield('line');
	addfield('checkbox','Users can start a new laundry cycle','allow_laundry_startcycle');
	addfield('checkbox','Users can block residents from using the laundry','allow_laundry_block');
	addfield('line');
	addfield('checkbox','Users can add or remove Bicycle/sport items','allow_borrow_adddelete');
	

	addfield('line','','',array('aside'=>true));
	addfield('created','Created','created',array('aside'=>true));


	// place the form elements and data in the template
	$cmsmain->assign('data',$data);
	$cmsmain->assign('formelements',$formdata);
	$cmsmain->assign('formbuttons',$formbuttons);
	} else {
		trigger_error('You do not have access to this menu. Please ask your admin to change this!');
	}
