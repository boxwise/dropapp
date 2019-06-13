<?php
	$login = true;
	require_once('library/core.php');
	
	error_reporting(0);
	require_once('library/ajax/'.preg_replace("/[^a-z0-9-]/", "", $_GET['file']).'.php');
