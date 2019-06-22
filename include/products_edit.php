<?php

	$table = 'products';
	$action = 'products_edit';

	if($_POST) {

		$handler = new formHandler($table);

		$savekeys = array('name','gender_id', 'value', 'category_id', 'maxperadult', 'maxperchild', 'amountneeded', 'sizegroup_id', 'camp_id', 'comments');
		$id = $handler->savePost($savekeys);

		redirect('?action='.$_POST['_origin']);
	}

	$data = db_row('SELECT * FROM '.$table.' WHERE id = :id',array('id'=>$id));

	if (!$id) {
		$data['amountneeded'] = 3;
		$data['camp_id'] = $_SESSION['camp']['id'];
	}

	// open the template
	$cmsmain->assign('include','cms_form.tpl');
	addfield('hidden','','id');
	addfield('hidden','','camp_id');

	// put a title above the form
	$cmsmain->assign('title','Product');

	addfield('text','Name','name');
	addfield('select', 'Category', 'category_id', array('required'=>true, 'width'=>3, 'multiple'=>false, 'query'=>'SELECT id AS value, label FROM product_categories ORDER BY seq'));
	if($_SESSION['camp']['market']) addfield('text', ucwords($_SESSION['camp']['currencyname']),'value');
	if($_SESSION['camp']['market']) addfield('number','Estimated annual need per person','amountneeded',array('width'=>3,'required'=>true));

	addfield('line','','');
	addfield('select', 'Gender', 'gender_id', array('width'=>2, 'multiple'=>false, 'query'=>'SELECT *, id AS value FROM genders ORDER BY seq'));
	addfield('select', 'Sizegroup', 'sizegroup_id', array('required'=>true,'width'=>2, 'multiple'=>false, 'query'=>'SELECT *, id AS value FROM sizegroup ORDER BY seq'));
	addfield('line');
	addfield('textarea','Description','comments');
	addfield('line');
	$table = 'stock';
	if($id) addfield('list','In Stock','stock', array('width'=>10,'query'=>'
	SELECT stock.id AS id, stock.box_id, stock.items, stock.comments, g.label AS gender, p.name AS product, p.name AS product, s.label AS size, l.label AS location, l.visible FROM '.$table.'
	LEFT OUTER JOIN products AS p ON p.id = stock.product_id
	LEFT OUTER JOIN locations AS l ON l.id = stock.location_id LEFT OUTER JOIN genders AS g ON g.id = p.gender_id LEFT OUTER JOIN sizes AS s ON s.id = stock.size_id WHERE stock.product_id = '.$id, 'columns'=>array('box_id'=>'Box ID', 'product'=>'Product', 'gender'=>'Gender', 'size'=>'Size','items'=>'Items', 'location'=>'Location', 'comments'=>'Comments'),
'allowedit'=>true,'allowadd'=>false,'allowselect'=>true,'allowselectall'=>false, 'allowshowhide'=>false, 'action'=>'stock', 'redirect'=>true, 'allowsort'=>true));

/*
	addfield('line','','');
	addfield('number','Maximum per adult per two weeks','maxperadult',array('width'=>3));
	addfield('number','Maximum per child per two weeks','maxperchild',array('width'=>3));
*/

	addfield('line','','',array('aside'=>true));
	addfield('created','Created','created',array('aside'=>true));

	// place the form elements and data in the template
	$cmsmain->assign('data',$data);
	$cmsmain->assign('formelements',$formdata);
	$cmsmain->assign('formbuttons',$formbuttons);
