<?

	$table = 'families';
	$action = 'families_edit';

	if($_POST) {

		if($_POST['pass']) $_POST['pass'] = md5($_POST['pass']);
		
		$handler = new formHandler($table);

		$savekeys = array('name','adults','children','visible','email', 'container_id');
		if($_POST['pass']) $savekeys[] = 'pass';
		$id = $handler->savePost($savekeys);

		if($_POST['__action']=='submitandnew') redirect('?action='.$action);
		redirect('?action='.$_POST['_origin']);
	}

	// collect data for the form
	$data = db_row('SELECT * FROM '.$table.' WHERE id = :id',array('id'=>$id));

	if (!$id) {
		$data['visible'] = 1;
	}

	// open the template
	$cmsmain->assign('include','cms_form.tpl');
	addfield('hidden','','id');

	// put a title above the form
	$cmsmain->assign('title','Family');

	addfield('text','Name of family','name');
	addfield('html', 'Drop Coins', '<p class="form-control-static"><i class="fa fa-tint"></i> ' . db_value('SELECT SUM(drops) FROM transactions AS t WHERE people_id = :id',array('id'=>intval($id))) . ' </p>');
	addfield('line','','');
	addfield('select', 'Container', 'container_id', array('required'=>true,'width'=>2, 'multiple'=>false, 'query'=>'SELECT *, id AS value, name AS label FROM containers ORDER BY name'));
	addfield('number', 'Number of adults', 'adults', array('width'=>2));
	addfield('number', 'Number of children', 'children', array('width'=>2));

	if($id){
		$table = 'people';
		addfield('list','Family members','people', array('width'=>10,'query'=>'SELECT people.*, DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), people.date_of_birth)), "%Y")+1 AS age FROM people WHERE parent_id = '.$id, 'columns'=>array('lastname'=>'Lastname', 'firstname'=>'Firstname', 'gender'=>'Gender', 'age'=>'Age'),
	'allowedit'=>true,'allowadd'=>false,'allowselect'=>false,'allowselectall'=>false, 'action'=>'people', 'redirect'=>true, 'allowsort'=>true));
	
			
		$table = 'transactions';
		addfield('list','Purchases','purch', array('width'=>10,'query'=>'SELECT t.*, u.naam AS user, CONCAT(IF(drops>0,"+",""),drops) AS drops2, DATE_FORMAT(transaction_date,"%Y-%m-%d %H:%i") AS tdate, s.label AS size, p.name AS product FROM transactions AS t LEFT OUTER JOIN cms_users AS u ON u.id = t.user_id LEFT OUTER JOIN products AS p ON p.id = t.product_id LEFT OUTER JOIN sizes AS s ON s.id = t.size_id WHERE people_id = '.$id. ' AND t.product_id != 0', 'columns'=>array('product'=>'Product', 'size'=>'Size', 'count'=>'Amount', 'drops2'=>'Drop Coins', 'description'=>'Note','user'=>'Purchase made by', 'tdate'=>'Date'),
	'allowedit'=>false,'allowadd'=>false,'allowselect'=>true,'allowselectall'=>false, 'action'=>'transactions', 'redirect'=>true, 'allowsort'=>true));
	
		addfield('html', '', '<a class="btn btn-success btn-xs" href="?action=purchase&family_id='.intval($id).'">New Purchase</a>');
	
		$table = 'transactions';
		addfield('list','Transactions','trans', array('width'=>10,'query'=>'SELECT t.*, u.naam AS user, CONCAT(IF(drops>0,"+",""),drops) AS drops2, DATE_FORMAT(transaction_date,"%Y-%m-%d %H:%i") AS tdate FROM transactions AS t LEFT OUTER JOIN cms_users AS u ON u.id = t.user_id WHERE people_id = '.$id. ' AND t.product_id = 0', 'columns'=>array('drops2'=>'Drop Coins', 'description'=>'Note','user'=>'Transaction made by', 'tdate'=>'Date'),
	'allowedit'=>false,'allowadd'=>false,'allowsort'=>true,'allowselect'=>true,'allowselectall'=>false, 'action'=>'transactions', 'redirect'=>true));
	
		addfield('html', '', '<a class="btn btn-success btn-xs" href="?action=give&ids='.intval($id).'"><i class="fa fa-tint"></i> Give Coins</a>');
	}
	
	addfield('line','','');
	addfield('text','Email address','email', array('disableautocomplete'=>true));
	addfield('password','Password','pass', array('disableautocomplete'=>true));


	addfield('checkbox','Visible','visible',array('aside'=>true));
	addfield('line','','',array('aside'=>true));
	addfield('created','Created','created',array('aside'=>true));

	addformbutton('submitandnew','Save and new family');

	// place the form elements and data in the template
	$cmsmain->assign('data',$data);
	$cmsmain->assign('formelements',$formdata);
	$cmsmain->assign('formbuttons',$formbuttons);

