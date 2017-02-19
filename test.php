<?php
	require('core.php');
	$result = db_query('SELECT s.* FROM stock AS s, locations AS l WHERE s.location_id = l.id AND l.camp_id = 2');
	while($row = db_fetch($result)) {
		echo "Box ".$row['box_id']." has product_id ".$row['product_id']."<br />";
		$product = db_row('SELECT * FROM products WHERE id = :id',array('id'=>$row['product_id']));
		$newid = db_value('SELECT id FROM products WHERE name = :name AND gender_id = :gender_id AND sizegroup_id = :sizegroup_id AND camp_id = 2',array('name'=>$product['name'],'gender_id'=>$product['gender_id'],'sizegroup_id'=>$product['sizegroup_id']));
		echo "New product_id is be ".$newid."<br />";
		db_query('UPDATE stock SET product_id = :product_id WHERE id = :id',array('product_id'=>$newid,'id'=>$row['id']));
	}
