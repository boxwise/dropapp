<?php
	require_once('core.php');
	
	if(!$_SESSION['user']['is_admin']) die('Go away!');
	
	echo "<strong>Database update script</strong><br />";

	if(!db_fieldexists('adminonly','cms_functions')) {
		echo "Created field 'adminonly' in table 'cms_functions'<br />";
		db_query('ALTER TABLE `cms_functions` ADD `adminonly` TINYINT  NOT NULL  DEFAULT "0"  AFTER `title_ar`');
	} else {
		echo "Field 'adminonly' in table 'cms_functions' already exists<br />";
	}

	if(!db_fieldexists('people','camp_id')) {
		echo "Created field 'camp_id' in table 'people'<br />";
		db_query('ALTER TABLE `people` ADD `camp_id` INT  NOT NULL  DEFAULT 0  AFTER `comments`;');
		db_query('UPDATE people SET camp_id = 1');
	} else {
		echo "Field 'camp_id' in table 'people' already exists<br />";
	}

	if(!db_fieldexists('products','camp_id')) {
		echo "Created field 'camp_id' in table 'products'<br />";
		db_query('ALTER TABLE `products` ADD `camp_id` INT  NOT NULL  DEFAULT 0  AFTER `sizegroup_id`;');
		db_query('UPDATE products SET camp_id = 1');
		db_query('CREATE TEMPORARY TABLE tmptable_1 SELECT * FROM products;');
		db_query('UPDATE tmptable_1 SET camp_id = 2, id = NULL');
		db_query('INSERT INTO products SELECT * FROM tmptable_1;');
		db_query('DROP TEMPORARY TABLE IF EXISTS tmptable_1;');

		$result = db_query('SELECT s.* FROM stock AS s, locations AS l WHERE s.location_id = l.id AND l.camp_id = 2');
		while($row = db_fetch($result)) {
			echo "Box ".$row['box_id']." has product_id ".$row['product_id']."<br />";
			$product = db_row('SELECT * FROM products WHERE id = :id',array('id'=>$row['product_id']));
			$newid = db_value('SELECT id FROM products WHERE name = :name AND gender_id = :gender_id AND sizegroup_id = :sizegroup_id AND camp_id = 2',array('name'=>$product['name'],'gender_id'=>$product['gender_id'],'sizegroup_id'=>$product['sizegroup_id']));
			echo "New product_id is be ".$newid."<br />";
			db_query('UPDATE stock SET product_id = :product_id WHERE id = :id',array('product_id'=>$newid,'id'=>$row['id']));
		}


	} else {
		echo "Field 'camp_id' in table 'products' already exists<br />";
	}
