<?php

$action = 'sales_list_export';
$cancel = 'sales_list';

$start = strftime('%Y-%m-%d', strtotime((string) $_SESSION['salesstart']));
$end = strftime('%Y-%m-%d', strtotime((string) $_SESSION['salesend']));

$query = db_query('
	SELECT tran.people_id AS familyhead, cat.label AS product_category, pro.name AS product, gen.label AS gender, tran.count AS amount, -tran.drops AS price, tran.transaction_date AS transaction_date,
		(SELECT COUNT(people.id)+1
			FROM people
			WHERE people.parent_id = tran.people_id
		) AS beneficiaries
	FROM (transactions AS tran, people AS pp)
	LEFT OUTER JOIN products AS pro ON tran.product_id = pro.id
	LEFT OUTER JOIN product_categories AS cat ON pro.category_id = cat.id
	LEFT OUTER JOIN genders AS gen ON pro.gender_id = gen.id
	WHERE tran.people_id = pp.id AND pp.camp_id = '.$_SESSION['camp']['id'].' AND tran.product_id > 0 AND tran.transaction_date >= "'.$start.' 00:00" AND tran.transaction_date <= "'.$end.' 23:59"
	ORDER BY tran.id');

$keys = ['familyhead' => 'ID of Familyhead', 'beneficiaries' => 'Number of Family Members', 'product_category' => 'Product Category', 'product' => 'Product', 'gender' => 'Gender', 'amount' => 'Quantity', 'price' => $_SESSION['camp']['currencyname'], 'transaction_date' => 'Transaction Date'];
csvexport($query, 'Sales_from_'.$start.'_until_'.$end, $keys);
