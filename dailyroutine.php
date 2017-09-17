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
	
	$result = db_query('
SELECT p.id, p.lastname,
	IF( (SELECT transaction_date FROM transactions AS t WHERE t.people_id = p.id ORDER BY transaction_date DESC LIMIT 1) >  IF(p.modified,p.modified,p.created), (SELECT transaction_date FROM transactions AS t WHERE t.people_id = p.id ORDER BY transaction_date DESC LIMIT 1), IF(p.modified,p.modified,p.created)) AS modified,
	DATEDIFF(NOW(), IF( (SELECT transaction_date FROM transactions AS t WHERE t.people_id = p.id ORDER BY transaction_date DESC LIMIT 1) >  IF(p.modified,p.modified,p.created), (SELECT transaction_date FROM transactions AS t WHERE t.people_id = p.id ORDER BY transaction_date DESC LIMIT 1), IF(p.modified,p.modified,p.created))) AS daysnotmodified
FROM people AS p 
LEFT OUTER JOIN camps AS c ON c.id = p.camp_id 
WHERE 
	NOT deleted AND
	p.parent_id = 0 AND 
	DATEDIFF(NOW(), IF( (SELECT transaction_date FROM transactions AS t WHERE t.people_id = p.id ORDER BY transaction_date DESC LIMIT 1) >  IF(p.modified,p.modified,p.created), (SELECT transaction_date FROM transactions AS t WHERE t.people_id = p.id ORDER BY transaction_date DESC LIMIT 1), IF(p.modified,p.modified,p.created))) > c.delete_inactive_users
ORDER BY daysnotmodified
	 ');
	 
	while($row = db_fetch($result)) {
		db_query('UPDATE people SET deleted = NOW() WHERE id = :id',array('id'=>$row['id']));
		simpleSaveChangeHistory('people', $id, 'Record deleted by daily routine');
	}
	
	// cleaning up the database in case of errors
	// delete children without a parent
	db_query('UPDATE people p1 LEFT JOIN people p2 ON p1.parent_id = p2.id SET p1.deleted=NOW() WHERE p2.ID IS NULL AND p1.parent_id != 0');
	
	// delete children with a deleted parent
	db_query('UPDATE people p1 LEFT JOIN people p2 ON p1.parent_id = p2.id SET p1.deleted=NOW() WHERE p2.deleted AND !p1.deleted AND p1.parent_id != 0');
	
	
	// this notifies us when a new installation of the Drop App is made
	if(!isset($settings['installed'])) {
		foreach($_SERVER as $key=>$value) $mail .= $key.' -> '.$value."<br />";
		$result = sendmail('post@bartdriessen.eu', 'post@bartdriessen.eu', 'New installation of Drop app', $mail);
		db_query('INSERT INTO settings (category_id, type, code, description_en, value) VALUES (1,"text","installed","Date and time of installation and first run",NOW())');
	}
	
