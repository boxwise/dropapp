<?php
	$login = true;
	require_once('core.php');
	require_once('ajax/'.preg_replace("/[^a-z0-9-]/", "", $_GET['file']).'.php');
