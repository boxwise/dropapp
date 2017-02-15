<?

	$table = 'transactions';
	$action = 'transactions_edit';

	if($_POST) {

		// open the template
		$cmsmain->assign('include','sales_graph.tpl');
		$cmsmain->assign('title','Sales overview');

		$date = strftime('%Y-%m-%d',strtotime($_POST['startdate']));
		$end = strftime('%Y-%m-%d',strtotime($_POST['enddate']));

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
			
/*
		$data = getlistdata('		
SELECT g.label AS gender, SUM(t.count) AS aantal FROM transactions AS t
LEFT OUTER JOIN products AS p ON t.product_id = p.id 
LEFT OUTER JOIN genders AS g ON p.gender_id = g.id 
WHERE t.product_id > 0 AND t.transaction_date >= "'.$start.' 00:00" AND t.transaction_date <= "'.$end.' 23:59"
GROUP BY p.gender_id');	
*/
	
		// place the form elements and data in the template
		$cmsmain->assign('data',$data);
		$cmsmain->assign('formelements',$formdata);
		$cmsmain->assign('formbuttons',$formbuttons);
	
		
	
	} else {
	
		// open the template
		$cmsmain->assign('include','cms_form.tpl');
		$cmsmain->assign('title','Sales overview');
	
		$translate['cms_form_submit'] = 'Make sales list';
		$cmsmain->assign('translate',$translate);

		$data['startdate'] = strftime('%Y-%m-%d');
		$data['enddate'] = strftime('%Y-%m-%d');
	
		addfield('date','Start date','startdate',array('date'=>true,'time'=>false));
		addfield('date','End date','enddate',array('date'=>true,'time'=>false));
		
		// place the form elements and data in the template
		$cmsmain->assign('data',$data);
		$cmsmain->assign('formelements',$formdata);
		$cmsmain->assign('formbuttons',$formbuttons);

	}


