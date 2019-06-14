<?php
	$table = 'cms_users';

	if($_SESSION['user']['is_admin'] || $_SESSION['usergroup']['userlevel'] > db_value('SELECT MIN(level) FROM cms_usergroups_levels')){

	if($_POST) {

		$keys = array('naam','email','cms_usergroups_id');

		$handler = new formHandler($table);
		$handler->savePost($keys);
		$row = db_row('SELECT * FROM '.$table.' WHERE id = :id ',array('id'=>$_SESSION['user']['id']));
		$_SESSION['user'] = array_merge($_SESSION['user'],$row);

		redirect('?action='.$_POST['_origin']);
	}

	// collect data for the form
	$data = db_row('SELECT * FROM '.$table.' WHERE id = :id',array('id'=>$id));

	if(!$_SESSION['user']['is_admin'] && $data['is_admin']) redirect('?action='.$_GET['origin']);

	// open the template
	$cmsmain->assign('include','cms_form.tpl');

	// put a title above the form
	$cmsmain->assign('title',$translate['cms_user']);

	// define tabs
	addfield('text',$translate['cms_users_naam'],'naam',array('required'=>true));
	addfield('email',$translate['cms_users_email'],'email',array('required'=>true,'tooltip'=>$translate['cms_users_email_tooltip']));
	
	$usergroups = db_array('
		SELECT id AS value, label 
		FROM cms_usergroups 
		WHERE organisation_id = :organisation_id 
		ORDER BY label',array('organisation_id'=>$_SESSION['organisation']['id']));
	addfield('select','Select user group','cms_usergroups_id',array('required'=>true,'options'=>$usergroups));
	
	if($data['lastlogin']=='0000-00-00 00:00:00') $data['lastlogin'] = '';
	addfield('info',$translate['cms_users_lastlogin'],'lastlogin',array('date'=>'true','time'=>'true'));
	addfield('line');

	addfield('created','Gemaakt','created',array('aside'=>true));

	$cmsmain->assign('data',$data);
	$cmsmain->assign('formelements',$formdata);
	} else {
		trigger_error('You do not have access to this menu. Please ask your admin to change this!');
	}
