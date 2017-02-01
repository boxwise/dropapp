<?

	include($_SERVER['DOCUMENT_ROOT'].'/flip/lib/functions.php');

	$table = 'stock';
	$action = 'stock_edit';

	if($_POST) {

		$handler = new formHandler($table);

		$savekeys = array('box_id', 'product_id', 'size_id', 'items', 'location_id', 'comments');
		$id = $handler->savePost($savekeys);

		if($_POST['__action']=='submitandnew') redirect('?action='.$action);
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
	$cmsmain->assign('title','Box');

	addfield('text','Box ID','box_id');

	addfield('select','Product','product_id',array('required'=>true,'multiple'=>false,'query'=>'SELECT p.id AS value, CONCAT(p.name, " " ,IFNULL(g.label,"")) AS label FROM products AS p LEFT OUTER JOIN genders AS g ON p.gender_id = g.id WHERE NOT p.deleted ORDER BY name', 'onchange'=>'getSizes()'));


	addfield('select', 'Size', 'size_id', array('required'=>true,'width'=>2, 'multiple'=>false, 'query'=>'SELECT *, id AS value FROM sizes WHERE sizegroup_id = '.intval(db_value('SELECT sizegroup_id FROM products WHERE id = :id',array('id'=>$data['product_id']))).' ORDER BY seq','tooltip'=>'If the right size for your box is not here, don\'t put it in comments, but first double check if you have the right product. For example: Long sleeves for babies, we call them tops.'));

	addfield('number','Items','items');

	addfield('select', 'Location', 'location_id', array('required'=>true,'width'=>2, 'multiple'=>false, 'query'=>'SELECT *, id AS value FROM locations ORDER BY seq'));
	
	if($data['qr_id']){
		$qr = db_value('SELECT code FROM qr WHERE id = :id',array('id'=>$data['qr_id']));
		
		addfield('html', '', '<img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=http://'.$_SERVER['HTTP_HOST'].'/flip/scan.php?barcode='.$qr.'" /><br /><br />', array('aside'=>true, 'asidetop'=>true));
	}
	
	addfield('line');
	addfield('textarea','Comments','comments');

/* 
	#these where added for the conversion from the google sheet 
	addfield('line');
	addfield('text','Old type','_type',array('readonly'=>true));
	addfield('text','Old gender','_gender',array('readonly'=>true));
	addfield('text','Old size','_size',array('readonly'=>true));
*/

	addfield('line','','',array('aside'=>true));
	addfield('created','Created','created',array('aside'=>true));

	addformbutton('submitandnew','Save and new item');

	// place the form elements and data in the template
	$cmsmain->assign('data',$data);
	$cmsmain->assign('formelements',$formdata);
	$cmsmain->assign('formbuttons',$formbuttons);

