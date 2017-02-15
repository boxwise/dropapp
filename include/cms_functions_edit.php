<?
	$table = 'cms_functions';

	if($_POST) {
	
		$handler = new formHandler($table);
		#$handler->debug = true;
		$keys = array();
		if(db_fieldexists($table,'title_'.$lan)) {
		 	$result = db_query('SELECT * FROM languages WHERE visible ORDER BY seq');
		 	while($row = db_fetch($result)) $keys[] = 'title_'.$row['code'];
		} else {
			$keys[] = 'title';
		}
		$handler->savePost(array_merge($keys,array('include','parent_id')));
		$handler->saveMultiple('modules','cms_access','cms_functions_id','cms_users_id');
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
 
	if(db_fieldexists($table,'title_'.$lan)) {
	 	$result = db_query('SELECT * FROM languages WHERE visible ORDER BY seq');
	 	while($row = db_fetch($result)) {
			addfield('text',$translate['cms_function'].' ('.$row['name'].')','title_'.$row['code'],array('required'=>true));
	 	}
	} else {
		addfield('text',$translate['cms_function'],'title',array('required'=>true));
	}
	addfield('text',$translate['cms_function_include'],'include');

	addfield('select',$translate['cms_function_users'],'modules',array('multiple'=>true,'query'=>'SELECT u.id AS value, u.naam AS label, IF(x.cms_users_id IS NOT NULL,1,0) AS selected FROM cms_users AS u LEFT OUTER JOIN cms_access AS x ON x.cms_users_id = u.id AND x.cms_functions_id = '.intval($id).' WHERE NOT u.deleted AND NOT u.is_admin ORDER BY u.naam'));
	addfield('select','Available for these camps','camps',array('multiple'=>true,'query'=>'SELECT a.id AS value, a.name AS label, IF(x.cms_functions_id IS NOT NULL, 1,0) AS selected FROM camps AS a LEFT OUTER JOIN cms_functions_camps AS x ON a.id = x.camps_id AND x.cms_functions_id = '.intval($id).' ORDER BY seq'));

	addfield('created','Gemaakt','created',array('aside'=>true));

	$cmsmain->assign('data',$data);
	$cmsmain->assign('formelements',$formdata);
	
