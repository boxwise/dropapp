<?

	include($_SERVER['DOCUMENT_ROOT'].'/flip/lib/functions.php');

	$table = 'containers';
	$action = 'containers_edit';

	if($_POST) {


		$handler = new formHandler($table);

		$savekeys = array('name');
		$id = $handler->savePost($savekeys);

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
	$cmsmain->assign('title','Container');

	addfield('text','Name','name');
	
	$table = 'families';
	addfield('list','Families','families', array('width'=>10,'query'=>'SELECT *, families.name AS familyname, families.id AS id FROM families LEFT OUTER JOIN containers AS c ON c.id = families.container_id WHERE families.container_id = '.$id, 'columns'=>array('familyname'=>'Family Name'),
'allowedit'=>true,'allowadd'=>false,'allowselect'=>false,'allowselectall'=>false, 'action'=>'families', 'redirect'=>true, 'allowsort'=>true));


	// place the form elements and data in the template
	$cmsmain->assign('data',$data);
	$cmsmain->assign('formelements',$formdata);
	$cmsmain->assign('formbuttons',$formbuttons);

