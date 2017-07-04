<?php

	$table = 'transactions';
	$action = 'sales_list';

	if($_POST) {

		# Save selected dates
		$_SESSION['salesstart'] = $_POST['startdate'];
		$_SESSION['salesend'] = $_POST['enddate'];

		$start = strftime('%Y-%m-%d',strtotime($_POST['startdate']));
		$end = strftime('%Y-%m-%d',strtotime($_POST['enddate']));
		$type = $_POST['type'][0];

		if($type=='graph') {

			$date = $start;

			while (strtotime($date) <= strtotime($end)) {
	            		$sales = db_value('SELECT COUNT(t.id) 
					FROM transactions AS t, people AS p 
					WHERE t.people_id = p.id AND p.camp_id = :camp_id AND t.product_id > 0 AND DATE_FORMAT(t.transaction_date,"%Y-%m-%d") = :date',
					array('date'=>$date,'camp_id'=>$_SESSION['camp']['id']));

				if($sales) {
					$test = db_simplearray('SELECT p.groupname AS gender, SUM(t.count) 
						AS aantal FROM (transactions AS t, people AS pp)
						LEFT OUTER JOIN products AS p ON t.product_id = p.id
						WHERE t.people_id = pp.id AND pp.camp_id = :camp_id AND t.product_id > 0 AND t.transaction_date >= "'.$date.' 00:00" AND t.transaction_date <= "'.$date.' 23:59"
						GROUP BY p.groupname ORDER BY SUM(t.count)',array('camp_id'=>$_SESSION['camp']['id']));
					foreach($test as $key=>$value) {
						$data[strftime("%a %e %b",strtotime($date))][$key] = $value;
					}
				}
	            	$date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
			}
			
			# Open Template
			$cmsmain->assign('include','sales_graph.tpl');
			$cmsmain->assign('title','Sales overview');
			$cmsmain->assign('data',$data);
			$cmsmain->assign('formelements',$formdata);
			$cmsmain->assign('formbuttons',$formbuttons);

		} else if ($type=='export') {
				
			redirect('?action=sales_list_download');

		} else {

			# General statements for all lists 
			initlist();

			# Total Sales and Drops added at each request at the bottom row
			$totalsales = db_value('SELECT SUM(t.count) AS aantal 
				FROM transactions AS t, people AS p
				WHERE t.people_id = p.id AND p.camp_id = :camp_id AND t.product_id > 0 AND t.transaction_date >= "'.$start.' 00:00" AND t.transaction_date <= "'.$end.' 23:59"', 
				array('camp_id'=>$_SESSION['camp']['id']));
			$totaldrops = -1*db_value('SELECT SUM(t.drops) AS aantal 
				FROM transactions AS t, people AS p
				WHERE t.people_id = p.id AND p.camp_id = :camp_id AND t.product_id > 0 AND t.transaction_date >= "'.$start.' 00:00" AND t.transaction_date <= "'.$end.' 23:59"',
				array('camp_id'=>$_SESSION['camp']['id']));

			if($type=='gender') {
				
				# Distribution of sales by gender
				$data = getlistdata('SELECT g.label AS gender, SUM(t.count) AS aantal 
					FROM (transactions AS t, people AS pp)
					LEFT OUTER JOIN products AS p ON t.product_id = p.id
					LEFT OUTER JOIN genders AS g ON p.gender_id = g.id
					WHERE t.people_id = pp.id AND pp.camp_id = '.$_SESSION['camp']['id'].' AND t.product_id > 0 AND t.transaction_date >= "'.$start.' 00:00" AND t.transaction_date <= "'.$end.' 23:59"
					GROUP BY p.gender_id');

				addcolumn('text','Gender','gender');
				addcolumn('text','Amount','aantal');
				$cmsmain->assign('listfooter',array('Total sales',$totalsales.' items ('.$totaldrops.' drops)'));

			} else {
				
				# Distribution of sales by products 
				$data = getlistdata('SELECT p.name, g.label AS gender, SUM(t.count) AS aantal 
					FROM (transactions AS t, people AS pp)
					LEFT OUTER JOIN products AS p ON t.product_id = p.id
					LEFT OUTER JOIN genders AS g ON p.gender_id = g.id
					WHERE t.people_id = pp.id AND pp.camp_id = '.$_SESSION['camp']['id'].' AND t.product_id > 0 AND t.transaction_date >= "'.$start.' 00:00" AND t.transaction_date <= "'.$end.' 23:59"
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

			# Open Template
			$cmsmain->assign('include','cms_list.tpl');
			$cmsmain->assign('title','Sales between '.$_POST['startdate'].' and '.$_POST['enddate']);
			$cmsmain->assign('data',$data);
			$cmsmain->assign('listconfig',$listconfig);
			$cmsmain->assign('listdata',$listdata);
		}

	} else {
				
		if($_SESSION['salesstart']) $data['startdate'] = $_SESSION['salesstart']; else $data['startdate'] = strftime('%Y-%m-%d',strtotime('-7 days'));
		if($_SESSION['salesend']) $data['enddate'] = $_SESSION['salesend']; else $data['enddate'] = strftime('%Y-%m-%d');
		$data['type'] = 'product';

		addfield('date','Start date','startdate',array('date'=>true,'time'=>false));
		addfield('date','End date','enddate',array('date'=>true,'time'=>false));
		addfield('line');
		addfield('select', 'Type', 'type', array('options'=>array(
			array('value'=>'graph', 'label'=>'Sales Graph'),
			array('value'=>'product', 'label'=>'By Product'),
			array('value'=>'gender', 'label'=>'By Gender'),
			array('value'=>'export', 'label'=>'All Sales (for Excel)'))));

		// open the template
		$cmsmain->assign('include','cms_form.tpl');
		// Title
		$cmsmain->assign('title','Sales overview');
		// Form Button
		$translate['cms_form_submit'] = 'Make sales list';
		$cmsmain->assign('translate',$translate);
		// place the form elements and data in the template
		$cmsmain->assign('data',$data);
		$cmsmain->assign('formelements',$formdata);
		$cmsmain->assign('formbuttons',$formbuttons);

	}
