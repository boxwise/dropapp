<?


	$table = 'people';
	$action = 'status';

	$id = $_SESSION['user']['id'];
	
	// collect data for the form
	$data = db_row('SELECT * FROM '.$table.' WHERE id = :id',array('id'=>$id));
	if (!$id) {
		$data['visible'] = 1;
	}

	// open the template
	$cmsmain->assign('include','status.tpl');
	addfield('hidden','','id');

	$data['children'] = db_numrows('SELECT *, TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) AS age FROM people WHERE parent_id = :id AND TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) < 16',array('id'=>$id));
	$data['children'] += db_numrows('SELECT *, TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) AS age FROM people WHERE id = :id AND TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) < 16',array('id'=>$id));
	$data['adults'] = db_numrows('SELECT *, TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) AS age FROM people WHERE parent_id = :id AND TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) >= 16',array('id'=>$id));
	$data['adults'] += db_numrows('SELECT *, TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) AS age FROM people WHERE id = :id AND TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) >= 16',array('id'=>$id));
	
	// put a title above the form
	$cmsmain->assign('title',$data['firstname'].' '.$data['lastname']);
	$cmsmain->assign('adults',$data['adults']);
	$cmsmain->assign('children',$data['children']);
	#$cmsmain->assign('container',db_value('SELECT name FROM containers WHERE id = '. $data['container']));
	$cmsmain->assign('dropcoins',db_value('SELECT SUM(drops) FROM transactions AS t WHERE people_id = :id',array('id'=>intval($id))));
	
		
	$table = 'transactions';
	addfield('list',$translate['purchases'],'purch', array('width'=>10,'query'=>'SELECT t.*, u.naam AS user, CONCAT(IF(drops>0,"+",""),drops) AS drops2, DATE_FORMAT(transaction_date,"%Y-%m-%d %H:%i") AS tdate, s.label AS size, p.name AS product FROM transactions AS t LEFT OUTER JOIN cms_users AS u ON u.id = t.user_id LEFT OUTER JOIN products AS p ON p.id = t.product_id LEFT OUTER JOIN sizes AS s ON s.id = t.size_id WHERE people_id = '.$id. ' AND t.product_id != 0', 'columns'=>array('product'=>$translate['product'], 'size'=>$translate['size'], 'count'=>$translate['amount'], 'drops2'=>$translate['coins'], 'description'=>$translate['note'], 'user'=>$translate['handled_by'], 'tdate'=>$translate['date']),
'allowedit'=>false,'allowadd'=>false,'allowselect'=>false,'allowselectall'=>false, 'action'=>'transactions', 'redirect'=>true, 'allowsort'=>true));

	$table = 'transactions';
	addfield('list',$translate['transactions'],'trans', array('width'=>10,'query'=>'SELECT t.*, u.naam AS user, CONCAT(IF(drops>0,"+",""),drops) AS drops2, DATE_FORMAT(transaction_date,"%Y-%m-%d %H:%i") AS tdate FROM transactions AS t LEFT OUTER JOIN cms_users AS u ON u.id = t.user_id WHERE people_id = '.$id. ' AND t.product_id = 0', 'columns'=>array('drops2'=>$translate['coins'], 'description'=>$translate['note'], 'user'=>$translate['handled_by'], 'tdate'=>$translate['date']),
'allowedit'=>false,'allowadd'=>false,'allowsort'=>true,'allowselect'=>false,'allowselectall'=>false, 'action'=>'transactions', 'redirect'=>true));

	// place the form elements and data in the template
	$cmsmain->assign('data',$data);
	$cmsmain->assign('formelements',$formdata);
	$cmsmain->assign('formbuttons',$formbuttons);

