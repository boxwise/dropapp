<?php
	$login = true;

	if($_GET['hash']&&($_GET['userid']||$_GET['peopleid'])) {
		require('include/cms_reset2.php');
	} else {
		require('include/cms_reset.php');
	}
