<?php


	$start = strftime('%Y-%m-%d',strtotime($_SESSION['salesstart']));
	$end = strftime('%Y-%m-%d',strtotime($_SESSION['salesend']));
	
	$query='SELECT pro.name AS product, gen.label AS gender, tran.count AS amount, -tran.drops AS price, tran.transaction_date AS transaction_date 
	FROM (transactions AS tran, people AS pp)
	LEFT OUTER JOIN products AS pro ON tran.product_id = pro.id
	LEFT OUTER JOIN genders AS gen ON pro.gender_id = gen.id
	WHERE tran.people_id = pp.id AND pp.camp_id = '.$_SESSION['camp']['id'].' AND tran.product_id > 0 AND tran.transaction_date >= "'.$start.' 00:00" AND tran.transaction_date <= "'.$end.' 23:59"
	ORDER BY tran.id';
	
	#$query='Select pro.name As product From transactions AS tran';


	$keys = array("product","gender","amount","price","transaction_date");
	csvexport($query,"Sales",$keys);
	redirect('?action=sales_list');