<?
	$login = true;
	require('flip.php');
	require('ajax/'.preg_replace("/[^a-z0-9-]/", "", $_GET['file']).'.php');