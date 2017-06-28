<?php

	$action = 'sales_list_download';
	$cancel = 'sales_list';

	$start = strftime('%Y-%m-%d',strtotime($_SESSION['salesstart']));
	$end = strftime('%Y-%m-%d',strtotime($_SESSION['salesend']));

	$query='SELECT pro.name AS product, gen.label AS gender, tran.count AS amount, -tran.drops AS price, tran.transaction_date AS transaction_date 
	FROM (transactions AS tran, people AS pp)
	LEFT OUTER JOIN products AS pro ON tran.product_id = pro.id
	LEFT OUTER JOIN genders AS gen ON pro.gender_id = gen.id
	WHERE tran.people_id = pp.id AND pp.camp_id = '.$_SESSION['camp']['id'].' AND tran.product_id > 0 AND tran.transaction_date >= "'.$start.' 00:00" AND tran.transaction_date <= "'.$end.' 23:59"
	ORDER BY tran.id';

	if($_GET['export']) {
		
		#Download data as .csv
		$transactions_export= db_array($query);

		header('Content-Type: application/csv');
		header('Content-Disposition: attachment; filename=sales_'.$start.'_'.$end.'csv');
		header('Pragma: no-cache');
		echo "Product,Gender,Amount,Price,Date\n";
		foreach($transactions_export as $tran) {
			echo $tran['product'].','.$tran['gender'].','.$tran['amount'].','.$tran['price'].','.$tran['transaction_date']."\n";
		}	
		die();

	} else {

		# Open Template (possible variables title, text, cancel, action
		$cmsmain->assign('include','download.tpl');
		$cmsmain->assign('title','Sales between '.$start.' and '.$end);
		$cmsmain->assign('text', array('title'=>'Export to .csv-file (for Excel or Google Docs)', 'body'=> 'The requested data is usually too large to be displayed. Please download it instead!'));
		$cmsmain->assign('cancel', $cancel);

	}
