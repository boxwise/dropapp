<?
	
	# create a table for the camps (2017-02-06 BD)
	if(!db_tableexists('camps')) {
		db_query('CREATE TABLE `camps` (
	  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	  `name` varchar(255) DEFAULT NULL,
	  `seq` int(11) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;');		
	}

	# add the last_update setting and set it to current timestamp (2017-02-06 BD)
	if(!isset($settings['last_update'])) {
		db_query("INSERT INTO `settings` (`category_id`, `type`, `code`, `description_nl`, `description_en`, `options`, `value`, `hidden`)
VALUES
	(1, 'text', 'last_update', 'Datum dat update script voor het laatst is gedraaid', 'Last time that the update script did run', '', UNIX_TIMESTAMP(), 0);");

	}