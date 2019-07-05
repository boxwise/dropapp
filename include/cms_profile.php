<?php
	$table = 'cms_users';

	if($_POST) {

		$keys = array('naam','email','language');
		if($_POST['pass']) {
			$_POST['pass'] = md5($_POST['pass']);
			array_push($keys, 'pass');
		}

		$handler = new formHandler($table);
		$handler->savePost($keys);

		$row = db_row('SELECT * FROM '.$table.' WHERE id = :id ',array('id'=>$_SESSION['user']['id']));
		$_SESSION['user'] = array_merge($_SESSION['user'],$row);

		redirect('?action='.$action);
	}

	// collect data for the form

	$data = db_row('SELECT * FROM '.$table.' WHERE id = :id',array('id'=>$_SESSION['user']['id']));

	// open the template
	$cmsmain->assign('include','cms_form.tpl');

	// put a title above the form
	$cmsmain->assign('title',$translate['cms_users_settings']);


	// define tabs

	addfield('text',$translate['cms_users_naam'], 'naam', array('required'=>true, 'readonly'=>($_SESSION['user']['usertype']=='family')));
	addfield('line');

	addfield('email',$translate['cms_users_email'],'email',array('required'=>true));
	addfield('password',$translate['cms_users_password'],'pass',array('repeat'=>true));

	#addfield('line');
	#addfield('select',$translate['cms_settings_language'],'language',array('query'=>'SELECT id AS value, name AS label FROM languages WHERE visible ORDER BY seq'));

	addfield('delete_user','Delete','',array('aside'=>true));
	addfield('created','Created','created',array('aside'=>true));
	

	$cmsmain->assign('data',$data);
	$cmsmain->assign('formelements',$formdata);
