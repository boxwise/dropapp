<?

	$table = 'transactions';
	$action = 'transactions_edit';

	if($_POST) {

		#dump($_POST);
		
		$_SESSION['salesstart'] = $_POST['startdate'];
		$_SESSION['salesend'] = $_POST['enddate'];
		
		$start = strftime('%Y-%m-%d',strtotime($_POST['startdate']));
		$end = strftime('%Y-%m-%d',strtotime($_POST['enddate']));
		$type = $_POST['type'][0];
		
		if($type=='graph') {

			$cmsmain->assign('include','sales_graph.tpl');
			$cmsmain->assign('title','Sales overview');

			$date = $start;

			while (strtotime($date) <= strtotime($end)) {
	            $sales = db_value('SELECT COUNT(id) FROM transactions AS t WHERE t.product_id > 0 AND DATE_FORMAT(t.transaction_date,"%Y-%m-%d") = :date',array('date'=>$date));
	
				if($sales) {
					$test = db_simplearray('SELECT p.groupname AS gender, SUM(t.count) AS aantal FROM transactions AS t
	LEFT OUTER JOIN products AS p ON t.product_id = p.id 
	WHERE t.product_id > 0 AND t.transaction_date >= "'.$date.' 00:00" AND t.transaction_date <= "'.$date.' 23:59"
	GROUP BY p.groupname ORDER BY SUM(t.count)');
					foreach($test as $key=>$value) {
						$data[strftime("%a %e %b",strtotime($date))][$key] = $value;
					}
				}
	            $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
			}	

			$cmsmain->assign('data',$data);
			$cmsmain->assign('formelements',$formdata);
			$cmsmain->assign('formbuttons',$formbuttons);

		} else {
			initlist();
	
			$cmsmain->assign('title','Sales between '.$_POST['startdate'].' and '.$_POST['enddate']);

			$totalsales = db_value('
	SELECT SUM(t.count) AS aantal FROM transactions AS t
	WHERE t.product_id > 0 AND t.transaction_date >= "'.$start.' 00:00" AND t.transaction_date <= "'.$end.' 23:59"');
			$totaldrops = -1*db_value('
	SELECT SUM(t.drops) AS aantal FROM transactions AS t
	WHERE t.product_id > 0 AND t.transaction_date >= "'.$start.' 00:00" AND t.transaction_date <= "'.$end.' 23:59"');
	
			if($type=='gender') {
				$data = getlistdata('
		SELECT g.label AS gender, SUM(t.count) AS aantal FROM transactions AS t
		LEFT OUTER JOIN products AS p ON t.product_id = p.id 
		LEFT OUTER JOIN genders AS g ON p.gender_id = g.id 
		WHERE t.product_id > 0 AND t.transaction_date >= "'.$start.' 00:00" AND t.transaction_date <= "'.$end.' 23:59"
		GROUP BY p.gender_id');
				addcolumn('text','Gender','gender');
				addcolumn('text','Amount','aantal');
				$cmsmain->assign('listfooter',array('Total sales',$totalsales.' items ('.$totaldrops.' drops)'));
			} elseif($type=='size') {
				$data = getlistdata('SELECT s.label AS size, SUM(t.count) AS aantal FROM transactions AS t LEFT OUTER JOIN sizes AS s ON t.size_id = s.id WHERE t.product_id > 0 AND t.transaction_date >= "'.$start.' 00:00" AND t.transaction_date <= "'.$end.' 23:59"
GROUP BY t.size_id');
				addcolumn('text','Size','size');
				addcolumn('text','Amount','aantal');
				$cmsmain->assign('listfooter',array('Total sales',$totalsales.' items ('.$totaldrops.' drops)'));
			} else {
				$data = getlistdata('
	SELECT p.name, g.label AS gender, SUM(t.count) AS aantal FROM transactions AS t
	LEFT OUTER JOIN products AS p ON t.product_id = p.id 
	LEFT OUTER JOIN genders AS g ON p.gender_id = g.id 
	WHERE t.product_id > 0 AND t.transaction_date >= "'.$start.' 00:00" AND t.transaction_date <= "'.$end.' 23:59"
	GROUP BY t.product_id');
				addcolumn('text','Product','name');
				addcolumn('text','Gender','gender');
				addcolumn('text','Amount','aantal');
				$cmsmain->assign('listfooter',array('Total sales','',$totalsales.' items ('.$totaldrops.' drops)'));
			}
	
			listsetting('allowcopy', false);
			listsetting('allowedit', false);
			listsetting('allowadd', false);
			listsetting('allowdelete', false);
			listsetting('allowselect', false);
			listsetting('allowselectall', false);
			listsetting('allowsort', true);
	
			$cmsmain->assign('data',$data);		
			$cmsmain->assign('listconfig',$listconfig);
			$cmsmain->assign('listdata',$listdata);
			$cmsmain->assign('include','cms_list.tpl');
		}
		
		
	
	} else {
	
		// open the template
		$cmsmain->assign('include','cms_form.tpl');
		$cmsmain->assign('title','Sales overview');
	
		$translate['cms_form_submit'] = 'Make sales list';
		$cmsmain->assign('translate',$translate);

		if($_SESSION['salesstart']) $data['startdate'] = $_SESSION['salesstart']; else $data['startdate'] = strftime('%Y-%m-%d',strtotime('-7 days'));
		if($_SESSION['salesend']) $data['enddate'] = $_SESSION['salesend']; else $data['enddate'] = strftime('%Y-%m-%d');
		$data['type'] = 'product';
		
		addfield('date','Start date','startdate',array('date'=>true,'time'=>false));
		addfield('date','End date','enddate',array('date'=>true,'time'=>false));
		addfield('line');

		addfield('select', 'Type', 'type', array('options'=>array(
			array('value'=>'product', 'label'=>'By Product'), 
			array('value'=>'graph', 'label'=>'Sales Graph'), 
			array('value'=>'gender', 'label'=>'By Gender'), 
			array('value'=>'size', 'label'=>'By Size'))));
		
		// place the form elements and data in the template
		$cmsmain->assign('data',$data);
		$cmsmain->assign('formelements',$formdata);
		$cmsmain->assign('formbuttons',$formbuttons);

	}


