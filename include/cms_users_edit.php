<?php
	$table = 'cms_users';

	if($_POST) {

		$keys = array('naam','email','coordinator');
		if($_POST['pass']) {
			$_POST['pass'] = md5($_POST['pass']);
			array_push($keys, 'pass');
		}

		$handler = new formHandler($table);
		$handler->savePost($keys);
		$handler->saveMultiple('modules', 'cms_access', 'cms_users_id', 'cms_functions_id');
		$handler->saveMultiple('camps', 'cms_users_camps', 'cms_users_id', 'camps_id');
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
	addfield('line');

	addfield('email',$translate['cms_users_email'],'email',array('required'=>true,'tooltip'=>$translate['cms_users_email_tooltip']));
	addfield('checkbox', 'Coordinator', 'coordinator');
	addfield('custom','','<h3>'.$translate['cms_users_password_change'].'</h3>');
	addfield('password',$translate['cms_users_password'],'pass',array('repeat'=>true,'tooltip'=>$translate['cms_users_password_tooltip']));
	addfield('line');

	addfield('info',$translate['cms_users_lastlogin'],'lastlogin',array('date'=>'true','time'=>'true'));
	addfield('line');

	addfield('select','Available camps','camps',array('multiple'=>true,'query'=>'SELECT a.id AS value, a.name AS label, IF(x.cms_users_id IS NOT NULL, 1,0) AS selected FROM camps AS a LEFT OUTER JOIN cms_users_camps AS x ON a.id = x.camps_id AND x.cms_users_id = '.intval($id).' ORDER BY seq'));

	addfield('select',$translate['cms_users_access'],'modules',array('multiple'=>true,'query'=>'
	SELECT 
		a.id AS value, a.title_en AS label, IF(x.cms_users_id IS NOT NULL, 1,0) AS selected 
	FROM cms_functions AS a 
	LEFT OUTER JOIN cms_access AS x ON a.id = x.cms_functions_id AND x.cms_users_id = '.intval($id).' 
	WHERE NOT a.adminonly AND NOT a.allusers AND a.parent_id != 0 ORDER BY a.title_en, seq'));

	if(db_tableexists('history')) {
		$changelog = array_keys(db_simplearray('SELECT CONCAT(changedate,", ",tablename,", ",record_id,": ",changes) FROM history WHERE user_id = :id ORDER BY changedate DESC',array('id'=>$data['id'])));
		foreach($changelog as $key=>$value) $changelog[$key] = str_replace(array("\n","\r"),"",strip_tags($changelog[$key]));
		$data['changelog'] = join("\r\n",$changelog);
		addfield('textarea','Acties','changelog',array('readonly'=>true,'rows'=>12));
	}

	addfield('created','Gemaakt','created',array('aside'=>true));

	$cmsmain->assign('data',$data);
	$cmsmain->assign('formelements',$formdata);
