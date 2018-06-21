<?php
	require_once('library/core.php');
	error_reporting(E_ALL);
	ini_set('display_errors',true);
	
	if(!$_SESSION['user']['is_admin']) die('Go away!');
	
	db_query('ALTER TABLE `stock` CHANGE `deleted` `deleted` DATETIME  NOT NULL  DEFAULT "0000-00-00 00:00:00"');

	echo "<strong>Database update script</strong><br />";

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


	}

	if(!db_fieldexists('locations','is_lost')) {
		echo "Created field 'is_lost' in table 'locations'<br />";
		db_query('ALTER TABLE `locations` ADD `is_lost` TINYINT(4) NOT NULL DEFAULT 0;');
		db_query('UPDATE locations SET is_lost = 1 WHERE UPPER(label) = "LOST";');
		$result = db_query('SELECT camp_id, MAX(is_lost) AS has_lost FROM locations GROUP BY camp_id');
		while($row = db_fetch($result)) {
			if(!$row['has_lost']) {
				db_query('INSERT INTO locations(label, camp_id, created, visible, is_lost, created_by) VALUES ("LOST", :id, NOW(), 0, 1, :user);', array('id' => $row['camp_id'],'user'=>$_SESSION['user']['id']));
			}
		}
	}
	
	if(!db_tableexists('laundry_machines')) {
		echo "Created table 'laundry_machines'<br />";
		db_query('CREATE TABLE `laundry_machines` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;');	
		db_query("INSERT INTO `laundry_machines` (`id`, `label`) VALUES (1, '1️⃣'), (2, '2️⃣'),(3, '3️⃣'),(4, '4️⃣ '),(5, '5️⃣'),(6, '6️⃣');");	
	}
	

	if(!db_tableexists('laundry_slots')) {
		echo "Created table 'laundry_slots'<br />";
		db_query('CREATE TABLE `laundry_slots` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `day` tinyint(4) DEFAULT NULL,
  `time` tinyint(4) DEFAULT NULL,
  `machine` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=302 DEFAULT CHARSET=utf8;');	
		$x = 1;
		for($day=0;$day<13;$day++) {
			if($day!=6) {
				for($time=1;$time<=5;$time++) {
					for($machine=1;$machine<=6;$machine++) {
						$x++;
						db_query('INSERT INTO laundry_slots (id, day, time, machine) VALUES (:id,:day,:time,:machine)',array('id'=>$x,'day'=>$day,'time'=>$time,'machine'=>$machine));
					}
	
				}
			}
		}
	}
	
	if(!db_tableexists('laundry_times')) {
		echo "Created table 'laundry_times'<br />";
		db_query('CREATE TABLE `laundry_times` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;');	
		db_query("INSERT INTO `laundry_times` (`id`, `label`) VALUES (1, '10:00 - 11:30'),(2, '11:30 - 1:00'),(3, '1:00 - 2:30'),(4, '2:30 - 4:00'),(5, '4:00 - 5:30');");	
	}
	
	if(!db_tableexists('laundry_appointments')) {
		echo "Created table 'laundry_appointments'<br />";
		db_query('CREATE TABLE `laundry_appointments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cyclestart` date DEFAULT NULL,
  `timeslot` int(11) DEFAULT NULL,
  `noshow` tinyint(4) NOT NULL DEFAULT 0,
  `people_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;');		
	}
	
	if(!db_tableexists('library')) {
		echo "Created table 'library'<br />";
		db_query("CREATE TABLE `library` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) DEFAULT NULL,
  `booktitle_en` varchar(255) DEFAULT NULL,
  `booktitle_ar` varchar(255) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `level_id` int(11) DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `visible` tinyint(4) NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
	} 

	if(db_tableexists('library_level')) {
		echo "Dropped table 'library_level'<br />";
		db_query("DROP TABLE `library_level`");
	}

	if(!db_tableexists('library_type')) {
		echo "Created table 'library_type'<br />";
		db_query("CREATE TABLE `library_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;");
		db_query("INSERT INTO `library_type` (`id`, `label`)
VALUES
 	(1, 'Arabic Children'),
	(12, 'Arabic English Fiction'),
	(13, 'Arabic Fiction'),
	(14, 'Arabic Non Fiction'),
	(15, 'Arabic Young Adult'),
	(16, 'Catalan Children'),
	(17, 'Dictionaries and Encyclopedias'),
	(18, 'Educational Books'),
	(19, 'English Children'),
	(20, 'English Fiction'),
	(21, 'English Non Fiction'),
	(22, 'English Young Adult'),
	(23, 'Farsi'),
	(24, 'French'),
	(25, 'French Children'),
	(26, 'Graded Readers'),
	(27, 'Graded Readers Non Fiction'),
	(28, 'Kurdish'),
	(29, 'Other'),
	(30, 'Portuguese Children Books'),
	(31, 'Urdu');
");
	}

	if(!db_tableexists('library_transactions')) {
		echo "Created table 'library_transactions'<br />";
		db_query('CREATE TABLE `library_transactions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `transaction_date` datetime DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `people_id` int(11) DEFAULT NULL,
  `status` varchar(5) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;');
	}
	
	/* Bicycle and borrowing */
	if(db_tableexists('bicycles')) {
		db_query('RENAME TABLE `bicycles` TO `borrow_items`');
		db_query('UPDATE cms_functions SET include = "borrow", title_en = "Borrow items" WHERE include = "bicycles"');
		db_query('UPDATE cms_functions SET title_en = "Borrow items" WHERE id = 131');
	}
	if(db_tableexists('bicycle_transactions')) {
		db_query('RENAME TABLE `bicycle_transactions` TO `borrow_transactions`');
	}
	if(!db_tableexists('borrow_items')) {
		echo "Created table 'borrow_items'<br />";
		db_query("CREATE TABLE `borrow_items` (`id` int(11) unsigned NOT NULL AUTO_INCREMENT, `label` varchar(255) DEFAULT NULL, `deleted` tinyint(4) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8; LOCK TABLES `bicycles` WRITE; INSERT INTO `bicycles` (`id`, `label`, `deleted`) VALUES (1,'Bike 1',0), (2,'Bike 2',0), (3,'Bike 3',0), (4,'Bike 4',0), (5,'Bike 5',0), (6,'Bike 6',0), (7,'Bike 7',0), (8,'Bike 8',0), (9,'Bike 9',0), (10,'Bike 10',0), (11,'Bike 11',0), (12,'Bike 12',0), (13,'Bike 13',0), (14,'Bike 14',0), (15,'Bike 15',0), (16,'Bike 16',0), (17,'Bike 17',0), (18,'Bike 18',0), (19,'Bike 19',0), (20,'Bike 20',0); UNLOCK TABLES;");
	}
	if(!db_tableexists('borrow_transactions')) {
		echo "Created table 'borrow_transactions'<br />";
		db_query("CREATE TABLE `borrow_transactions` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `transaction_date` datetime DEFAULT NULL, `bicycle_id` int(11) DEFAULT NULL, `people_id` int(11) DEFAULT NULL, `status` varchar(5) DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;	
");
	}
	if(!db_tableexists('borrow_categories')) {
		echo "Created table 'borrow_categories'<br />";
		db_query("CREATE TABLE `borrow_categories` (`id` int(11) unsigned NOT NULL AUTO_INCREMENT, `label` varchar(255) DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8; LOCK TABLES `borrow_categories` WRITE; INSERT INTO `borrow_categories` (`id`, `label`) VALUES (1,'Bicycles'), (2,'Gym gear'); UNLOCK TABLES;");
	}
	if(!db_tableexists('x_people_languages')) {
		echo "Created table 'x_people_languages'<br />";
		db_query("CREATE TABLE `x_people_languages` (`people_id` int(11) DEFAULT NULL, `language_id` int(11) DEFAULT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
	}
	
	if(db_fieldexists('cms_functions','title_nl')) {
		echo "+ Dropped field 'title_nl' in table 'cms_functions'<br />";
		db_query('ALTER TABLE `cms_functions` DROP `title_nl`');
	}
	
	if(db_fieldexists('people','languages')) {
		echo "+ Dropped field 'languages' in table 'people'<br />";
		db_query('ALTER TABLE `people` DROP `languages`');
	}
	

	db_addfield('products','comments',"VARCHAR(255)");	

	db_addfield('camps','schedulestart',"VARCHAR(255)");	
	db_addfield('camps','schedulestop',"VARCHAR(255)");	
	db_addfield('camps','schedulebreak',"VARCHAR(255)");	
	db_addfield('camps','schedulebreakstart',"VARCHAR(255)");	
	db_addfield('camps','schedulebreakduration',"VARCHAR(255)");	
	db_addfield('camps','scheduletimeslot',"VARCHAR(255)");	
	db_addfield('camps','dropsperadult',"VARCHAR(255)");	
	db_addfield('camps','dropsperchild',"VARCHAR(255)");	
	db_addfield('camps','cyclestart',"DATETIME NULL");	
	db_addfield('camps','laundry',"TINYINT NOT NULL  DEFAULT 0 AFTER `workshop`",'UPDATE camps SET laundry = 1 WHERE id = 1');	
	
	db_addfield('camps','dropcapadult',"INT  NOT NULL  DEFAULT 99999 ");	
	db_addfield('camps','dropcapchild',"INT  NOT NULL  DEFAULT 99999 ");	
	
	db_addfield('cms_functions','visible',"TINYINT NOT NULL",'UPDATE `cms_functions` SET `visible` = 1');	
	db_addfield('cms_functions','allusers',"TINYINT  NOT NULL ",'UPDATE `cms_functions` SET `visible` = 0');	
	db_addfield('cms_functions','adminonly',"TINYINT  NOT NULL  DEFAULT 0  AFTER `title_ar`");	

	db_addfield('cms_users','coordinator',"TINYINT  NOT NULL  DEFAULT 0");	
	
	db_addfield('borrow_items','category_id',"INT  NOT NULL  DEFAULT 0 ");	
	db_addfield('borrow_items','visible',"TINYINT(4)  NOT NULL  DEFAULT 0");	
	db_addfield('borrow_items','comment',"TEXT  NOT NULL");

	db_addfield('library','camp_id',"INT  NOT NULL  DEFAULT 1");

	db_addfield('library_type','camp_id',"INT  NOT NULL  DEFAULT 1");

	db_addfield('library_transactions','comment',"TEXT  NOT NULL");
	
	db_addfield('people','camp_id',"INT  NOT NULL  DEFAULT 0  AFTER `comments`;",'UPDATE people SET camp_id = 1');
	db_addfield('people','phone',"VARCHAR(255)  AFTER `comments`");
	db_addfield('people','bicycletraining',"INT  NOT NULL  DEFAULT 0  AFTER `comments`;");
	db_addfield('people','bicycleban',"DATE  NULL  AFTER `notregistered`");
	db_addfield('people','bicyclebancomment',"TEXT  NOT NULL");
	db_addfield('people','workshoptraining',"INT  NOT NULL  DEFAULT 0  AFTER `comments`;");
	db_addfield('people','workshopban',"DATE  NULL  AFTER `notregistered`");
	db_addfield('people','workshopsupervisor',"INT  NOT NULL  DEFAULT 0  AFTER `workshoptraining`;");
	db_addfield('people','workshopbancomment',"TEXT  NOT NULL");
	db_addfield('people','notregistered',"TINYINT  NOT NULL  DEFAULT 0");
	db_addfield('people','volunteer',"TINYINT  NOT NULL  DEFAULT 0");
	db_addfield('people','laundryblock',"TINYINT  NOT NULL  DEFAULT 0");
	db_addfield('people','laundrycomment',"VARCHAR(255)");

	db_addfield('laundry_appointments','dropoff',"TINYINT  NOT NULL  DEFAULT 0");
	db_addfield('laundry_appointments','collected',"TINYINT  NOT NULL  DEFAULT 0");
	db_addfield('laundry_appointments','comment',"TEXT  NOT NULL");
	
	db_addfield('borrow_transactions','lights',"TINYINT NOT NULL DEFAULT 0");
	
	db_addfield('camps','food',"TINYINT(4) NOT NULL DEFAULT '0' AFTER `bicycle`","UPDATE `camps` SET food = 1 WHERE name = 'Nea Kavala';");	
	db_addfield('camps','bicycle',"TINYINT(4) NOT NULL DEFAULT '0' AFTER `bicycle`","UPDATE `camps` SET bicycle = 1 WHERE name = 'Nea Kavala';");	
	db_addfield('camps','workshop',"TINYINT(4) NOT NULL DEFAULT '0' AFTER `bicycle`","UPDATE `camps` SET workshop = 1 WHERE name = 'Nea Kavala';");

	if(!db_value('SELECT * FROM product_categories WHERE label = "Food"')) db_query('INSERT INTO `product_categories` (`label`, `seq`) VALUES ("Food", 11);');
	
function db_addfield($table,$field,$options,$query = "") {
	if(!db_fieldexists($table,$field)) {
		db_query("ALTER TABLE `".$table."` ADD `".$field."` ".$options);
		echo "+ In table '".$table."' field '".$field."' added <br />";
		if($query) db_query($query);
	}
}