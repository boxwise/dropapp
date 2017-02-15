<?php
	if($_SESSION['user']['usertype']=='family') $table = 'people'; else $table = 'cms_users';

	if($_POST) {

		if($_SESSION['user']['usertype']=='family') {
			$_POST['name'] = $_POST['naam'];
			$table = 'people';
			$keys = array('firstname','lastname','email','language');
			if($_POST['pass']) {
				$_POST['pass'] = md5($_POST['pass']);
				array_push($keys, 'pass');
			}

			$handler = new formHandler($table);
			$handler->savePost($keys);

			$row = db_row('SELECT *, CONCAT(firstname," ",lastname) AS naam FROM '.$table.' WHERE id = :id ',array('id'=>$_SESSION['user']['id']));
			$_SESSION['user'] = array_merge($_SESSION['user'],$row);
		} else {
			$keys = array('naam','email','language');
			if($_POST['pass']) {
				$_POST['pass'] = md5($_POST['pass']);
				array_push($keys, 'pass');
			}

			$handler = new formHandler($table);
			$handler->savePost($keys);

			$row = db_row('SELECT * FROM '.$table.' WHERE id = :id ',array('id'=>$_SESSION['user']['id']));
			$_SESSION['user'] = array_merge($_SESSION['user'],$row);
		}

		redirect('?action='.$action);
	}

	// collect data for the form

	if($_SESSION['user']['usertype']=='family') {
		$data = db_row('SELECT * FROM '.$table.' WHERE id = :id',array('id'=>$_SESSION['user']['id']));
	} else {
		$data = db_row('SELECT * FROM '.$table.' WHERE id = :id',array('id'=>$_SESSION['user']['id']));
	}

	// open the template
	$cmsmain->assign('include','cms_form.tpl');

	// put a title above the form
	$cmsmain->assign('title',$translate['cms_users_settings']);


	// define tabs

	if($_SESSION['user']['usertype']=='family') {
		addfield('text',$translate['firstname'], 'firstname', array('required'=>true, 'readonly'=>($_SESSION['user']['usertype']=='family')));
		addfield('text',$translate['lastname'], 'lastname', array('required'=>true, 'readonly'=>($_SESSION['user']['usertype']=='family')));
	} else {
		addfield('text',$translate['cms_users_naam'], 'naam', array('required'=>true, 'readonly'=>($_SESSION['user']['usertype']=='family')));
	}
	addfield('line');

	addfield('email',$translate['cms_users_email'],'email',array('required'=>true));
	addfield('password',$translate['cms_users_password'],'pass',array('repeat'=>true));

	addfield('line');
	addfield('select',$translate['cms_settings_language'],'language',array('query'=>'SELECT id AS value, name AS label FROM languages WHERE visible ORDER BY seq'));


// 	addfield('info',$translate['cms_users_lastlogin'],'lastlogin',array('date'=>'true','time'=>'true'));

	addfield('created','Gemaakt','created',array('aside'=>true));

	$cmsmain->assign('data',$data);
	$cmsmain->assign('formelements',$formdata);
