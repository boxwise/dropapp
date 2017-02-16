<?php
	require('core.php');
	
	if(!$_SESSION['user']['is_admin']) die('Go away!');
	
	echo "<strong>Database update script</strong><br />";

	if(!db_fieldexists('people','camp_id')) {
		echo "Created field 'camp_id' in table 'people'<br />";
		db_query('ALTER TABLE `people` ADD `camp_id` INT  NOT NULL  DEFAULT 0  AFTER `comments`;');
		db_query('UPDATE people SET camp_id = 1');
	} else {
		echo "Field 'camp_id' in table 'people' already exists<br />";
	}

