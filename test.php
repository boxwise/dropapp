<?php
	die();
	
	require('library/core.php');
	
	$result = db_query('SELECT * FROM people AS p WHERE camp_id = 3 AND parent_id = 0 AND NOT deleted');
	while($row = db_fetch($result)) {
		$drops = db_value('SELECT SUM(drops) FROM transactions WHERE people_id = :people_id',array('people_id'=>$row['id']));
		echo $row['id'].' '.$row['firstname'].' '.$row['lastname'].' '.$drops.'<br />';
		db_query('INSERT INTO transactions (people_id, description, drops,transaction_date,user_id) VALUES (:people_id,"Manually reset to 0 drops",:drops,NOW(),1)',array('people_id'=>$row['id'],'drops'=>-$drops));
	}