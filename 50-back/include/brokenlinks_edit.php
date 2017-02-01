<?
	$table = 'brokenlinks';

	if($_POST) {

		redirect('?action='.$_POST['_origin']);
	}

	// collect data for the form
	$data = db_row('SELECT * FROM '.$table.' WHERE id = :id',array('id'=>$id));

	// open the template
	$cmsmain->assign('include','cms_form.tpl');

	// put a title above the form
	$cmsmain->assign('title',$translate['cms_brokenlinks_url']);

	// define tabs
	addfield('text',$translate['cms_brokenlinks_url'],'link',array('readonly'=>true));
	addfield('text',$translate['cms_brokenlinks_location'],'location',array('readonly'=>true));
	addfield('text',$translate['cms_brokenlinks_error'],'error',array('readonly'=>true));

	$cmsmain->assign('data',$data);
	$cmsmain->assign('formelements',$formdata);

