<?
	
	require('flip.php');
	require('functions.php');
	
	$result = db_query('SELECT * FROM products WHERE NOT deleted');
	while($row = db_fetch($result)) {
		echo $id.' ';
		makeproductimage($row['id']);
		flush();
	}