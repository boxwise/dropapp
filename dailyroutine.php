<?php
	
	// This file is called about one time daily

 	// this function sorts the people list on container/household id, giving the best possible overview
	$result = db_query('SELECT id, parent_id, people.container FROM people WHERE NOT deleted AND parent_id = 0 ORDER BY camp_id, IF(container="AAA1",1,0), IF(container="?",1,0), SUBSTRING(container, 1,1), SUBSTRING(container, 2, 10)*1');

	while($row = db_fetch($result)) {
		$i++;
		db_query('UPDATE people SET seq = '.$i.' WHERE id = '.$row['id']);

		$j=0;

		$result2 = db_query('SELECT id FROM people WHERE NOT deleted AND parent_id = '.$row['id'].' ORDER BY DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 DESC');
		while($row2 = db_fetch($result2)) {
			$j++;
			db_query('UPDATE people SET seq = '.$j.' WHERE id = '.$row2['id']);
		}
	}	


	// people that have not been active for a longer time will be deleted
	// the amount of days of inactivity is set in the camp table
	
	$result = db_query('SELECT p.id, p.lastname, IF(p.modified,p.modified,p.created) AS modified, DATEDIFF(NOW(), IF(p.modified,p.modified,p.created)) AS daysnotmodified
FROM people AS p 
LEFT OUTER JOIN camps AS c ON c.id = p.camp_id 
WHERE 
	NOT p.deleted AND 
	p.parent_id = 0 AND 
	DATEDIFF(NOW(), IF(p.modified,p.modified,p.created)) > c.delete_inactive_users ');
	 
	while($row = db_fetch($result)) {
		dump($row['lastname']);
		db_query('UPDATE people SET deleted = NOW() WHERE id = :id',array('id'=>$row['id']));
	}