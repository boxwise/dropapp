<?php
	require_once('library/core.php');
	error_reporting(E_ALL);
	ini_set('display_errors',true);
	
	if(!$_SESSION['user']['is_admin']) die('Go away!');
	
	echo "<strong>Database update script</strong><br />";
	
	
	
function db_addfield($table,$field,$options,$query = "") {
	if(!db_fieldexists($table,$field)) {
		db_query("ALTER TABLE `".$table."` ADD `".$field."` ".$options);
		echo "+ In table '".$table."' field '".$field."' added <br />";
		if($query) db_query($query);
	}
}

	echo "<br /><br /><a href='/'>Continue</a>";