<?php
	require_once('library/core.php');
	error_reporting(E_ALL);
	ini_set('display_errors',true);
	
	if(!$_SESSION['user']['is_admin']) die('Go away!');

	for($i=0;$i<=5;$i++) {
		for($j=1;$j<=6;$j++) {
			for($m=8;$m<=14;$m++) {
				echo 'INSERT INTO laundry_slots (day,time,machine) VALUES ('.$i.','.$j.','.$m.');<br />';
			}
		}
	}
	for($i=7;$i<=12;$i++) {
		for($j=1;$j<=6;$j++) {
			for($m=8;$m<=14;$m++) {
				echo 'INSERT INTO laundry_slots (day,time,machine) VALUES ('.$i.','.$j.','.$m.');<br />';
			}
		}
	}