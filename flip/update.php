<?
	
	if(!db_tableexists('cms_functions_camps')) {
		db_query('CREATE TABLE `cms_functions_camps` (`cms_functions_id` int(11) DEFAULT NULL, `camps_id` int(11) DEFAULT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
		db_query('INSERT INTO `cms_functions_camps` (`cms_functions_id`, `camps_id`)
VALUES (99, 1), (99, 2), (91, 1), (87, 1), (106, 1), (90, 1), (90, 2), (67, 1), (67, 2), (89, 1), (89, 2), (42, 1), (42, 2), (35, 1), (35, 2), (96, 1), (92, 1), (111, 1), (112, 1), (112, 2), (100, 1), (95, 1), (104, 1), (107, 1), (93, 1), (102, 1), (45, 1), (45, 2), (50, 1), (50, 2), (43, 1), (43, 2), (44, 1), (44, 2), (114, 1), (114, 2);
');
	}

	# create a table for the camps (2017-02-06 BD)
	if(!db_tableexists('camps')) {
		db_query('CREATE TABLE `camps` (
	  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	  `name` varchar(255) DEFAULT NULL,
	  `seq` int(11) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;');	
		db_query("INSERT INTO `camps` (`id`, `name`, `seq`) VALUES (1, 'Nea Kavala', 1), (2, 'Souda / Chios', 2);");
	}
	# make a cross ref table for users and camps (2017-02-06 BD)
	if(!db_tableexists('cms_users_camps')) {
		db_query('CREATE TABLE `cms_users_camps` (
  `cms_users_id` int(11) DEFAULT NULL,
  `camps_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;');		
	}
	# dedicate warehouse locations to camps (2017-02-06 BD)
	if(!db_fieldexists('locations','camp_id')) {
		db_query("ALTER TABLE `locations` ADD `camp_id` INT  NULL  DEFAULT NULL  AFTER `label`");
	}

	# add the last_update setting and set it to current timestamp (2017-02-06 BD)
	if(!isset($settings['last_update'])) {
		db_query("INSERT INTO `settings` (`category_id`, `type`, `code`, `description_nl`, `description_en`, `options`, `value`, `hidden`)
VALUES
	(1, 'text', 'last_update', 'Datum dat update script voor het laatst is gedraaid', 'Last time that the update script did run', '', UNIX_TIMESTAMP(), 0);");

	}