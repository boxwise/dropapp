<?php
	require('library/core.php');
	
		date_default_timezone_set('Europe/Athens');
	db_query('SET time_zone = "+03:00"');

echo db_value('SELECT NOW()');

echo strftime("%Y-%m-%d %H:%M:%S");