<?

	include($_SERVER['DOCUMENT_ROOT'].'/flip/lib/functions.php');

	$table = 'transactions';
	$action = 'transactions_edit';

	if($_POST) {

		#dump($_POST);
		
		$start = strftime('%Y-%m-%d',strtotime($_POST['startdate']));
		$end = strftime('%Y-%m-%d',strtotime($_POST['enddate']));
		
		initlist();

		$cmsmain->assign('title','Sales between '.$_POST['startdate'].' and '.$_POST['enddate']);

		$data = getlistdata('SELECT s.label AS size, SUM(t.count) AS aantal FROM transactions AS t LEFT OUTER JOIN sizes AS s ON t.size_id = s.id WHERE t.product_id > 0 AND t.transaction_date >= "'.$start.' 00:00" AND t.transaction_date <= "'.$end.' 23:59"
GROUP BY t.size_id');

		listsetting('allowcopy', false);
		listsetting('allowedit', false);
		listsetting('allowadd', false);
		listsetting('allowdelete', false);
		listsetting('allowselect', false);
		listsetting('allowselectall', false);
		listsetting('allowsort', true);

		addcolumn('text','Size','size');
		addcolumn('text','Amount','aantal');

		$cmsmain->assign('data',$data);
		$cmsmain->assign('listconfig',$listconfig);
		$cmsmain->assign('listdata',$listdata);
		$cmsmain->assign('include','cms_list.tpl');

	
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


