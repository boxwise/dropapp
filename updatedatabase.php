<?
	require('core.php');
	
	if(!$_SESSION['user']['is_admin']) die('Go away!');
	
	echo "<strong>Database update script</strong><br />";

	db_query('UPDATE cms_functions SET include = "fancygraphs" WHERE include = "demography"');

	if(!db_tableexists('products_prices')) {
		echo "Create table products_prices<br />";
		
		db_query('CREATE TABLE `products_prices` (`product_id` int(11) DEFAULT NULL, `camp_id` int(11) DEFAULT NULL, `price` int(11) DEFAULT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
		$result = db_query('SELECT * FROM products AS p');
		while($row = db_fetch($result)) {
			db_query('INSERT INTO products_prices (product_id, camp_id, price) VALUES ('.$row['id'].',1,'.$row['value'].')');
		}

	} else {
		echo "Table products_prices already exists<br />";
	}		
	if(!db_tableexists('cms_functions_camps')) {
		echo "Create table cms_functions_camps<br />";
		
		db_query('CREATE TABLE `cms_functions_camps` (`cms_functions_id` int(11) DEFAULT NULL, `camps_id` int(11) DEFAULT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
		db_query('INSERT INTO `cms_functions_camps` (`cms_functions_id`, `camps_id`)
VALUES (99, 1), (99, 2), (91, 1), (87, 1), (106, 1), (90, 1), (90, 2), (67, 1), (67, 2), (89, 1), (89, 2), (42, 1), (42, 2), (35, 1), (35, 2), (96, 1), (92, 1), (111, 1), (112, 1), (112, 2), (100, 1), (95, 1), (104, 1), (107, 1), (93, 1), (102, 1), (45, 1), (45, 2), (50, 1), (50, 2), (43, 1), (43, 2), (44, 1), (44, 2), (114, 1), (114, 2);
');
	} else {
		echo "Table cms_functions_camps already exists<br />";
	}

	# create a table for the camps (2017-02-06 BD)
	if(!db_tableexists('camps')) {
		echo "Create table camps<br />";

		db_query('CREATE TABLE `camps` (
	  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	  `name` varchar(255) DEFAULT NULL,
	  `seq` int(11) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;');	
		db_query("INSERT INTO `camps` (`id`, `name`, `seq`) VALUES (1, 'Nea Kavala', 1), (2, 'Souda / Chios', 2);");
	} else {
		echo "Table camps already exists<br />";
	}
	
	# make a cross ref table for users and camps (2017-02-06 BD)
	if(!db_tableexists('cms_users_camps')) {
		echo "Create table cms_users_camps<br />";

		db_query('CREATE TABLE `cms_users_camps` (
  `cms_users_id` int(11) DEFAULT NULL,
  `camps_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
		$result = db_query('SELECT * FROM cms_users WHERE NOT is_admin AND NOT deleted');
		while($row = db_fetch($result)) {
			db_query('INSERT INTO cms_users_camps (cms_users_id, camps_id) VALUES ('.$row['id'].',1)');
		}		
	} else {
		echo "Table cms_users_camps already exists<br />";
	}
	# dedicate warehouse locations to camps (2017-02-06 BD)
	if(!db_fieldexists('locations','camp_id')) {
		echo "Add camp_id to locations table<br />";

		db_query("ALTER TABLE `locations` ADD `camp_id` INT  NULL  DEFAULT NULL  AFTER `label`");
		db_query("UPDATE locations SET camp_id = 1");
	} else {
		echo "Locations table already has camp_id field<br />";
	}
	
	if(db_tableexists('containers')) { db_query('DROP TABLE containers'); echo "Removed table 'containers'<br />"; }
	if(db_tableexists('foodcycles')) { db_query('DROP TABLE foodcycles'); echo "Removed table 'foodcycles'<br />"; }
	if(db_tableexists('foodtransactions')) { db_query('DROP TABLE foodtransactions'); echo "Removed table 'foodtransactions'<br />"; }
	if(db_tableexists('ration')) { db_query('DROP TABLE ration'); echo "Removed table 'ration'<br />"; }
	
	if(!db_fieldexists('camps','require_qr')) {
		echo "Add require_qr to camps table<br />";

		db_query("ALTER TABLE `camps` ADD `require_qr` INT NULL DEFAULT NULL ");
		db_query("UPDATE camps SET require_qr = 1 WHERE id = 1");
		db_query("UPDATE camps SET require_qr = 0 WHERE id = 2");
	} else {
		echo "Camps table already has require_qr field<br />";
	}