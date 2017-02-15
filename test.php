<?php
	require('core.php');
	$result = db_query('SELECT * FROM transactions WHERE product_id != 0 ORDER BY transaction_date');
	$i=0;
	while($row = db_fetch($result)) {
		$i+=$row['count'];
		if($i>=10000) {
			dump($i);
			dump($row);
			die();
		}
	}
