<?php
	require('library/core.php');
	
	date_default_timezone_set('Europe/Athens');
	db_query('SET time_zone = "+'.(date('Z')/3600).':00"');

echo db_value('SELECT NOW()');
echo '<br />';


echo strftime("%Y-%m-%d %H:%M:%S");