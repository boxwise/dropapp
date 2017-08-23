<?php
	
	require_once($_SERVER["DOCUMENT_ROOT"].'/'.'library/core.php');
	
	if(!$_SESSION['user']['is_admin']) die('Go away!');
	
	$tables = db_listtables();
	
	foreach($tables as $table) {
		if(db_fieldexists($table, 'deleted')) {
			echo '"'.$table.'" has field "deleted"<br />';
			if(!db_fieldexists($table, 'deleteddate')) {
				db_query('ALTER TABLE '.$table.' ADD `deleteddate` DATETIME NOT NULL ');
			}
			$result = db_query('SELECT id, modified FROM '.$table.' WHERE deleted');
			while($row = db_fetch($result)) {
				#db_query('UPDATE '.$table.' SET deleteddate = modified');
				$modified = $row['modified']?$row['modified']:strftime('%Y-%m-%d %H:%M');
				echo $row['id'].' '.$modified.'<br />';
				db_query('UPDATE '.$table.' SET deleteddate = :modified WHERE id = :id',array('modified'=>$modified,'id'=>$row['id']));
			}
			db_query('ALTER TABLE '.$table.' DROP deleted');
			db_query('ALTER TABLE '.$table.' CHANGE `deleteddate` `deleted` DATETIME ');
		}
	}