<?php
	$login = true;

	if($_GET['hash']&&($_GET['userid']||$_GET['peopleid'])) {
		require_once('include/cms_reset2.php');
	} else {
		require_once('include/cms_reset.php');
	}
