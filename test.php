<?php
	require('library/core.php');
	
	$result = db_query('SELECT 
	p.*, SUM(t.drops) AS drops,
	IF((SELECT COUNT(id) FROM people WHERE volunteer AND (id = p.id OR parent_id = p.id)),99999,dropcapadult * (SELECT COUNT(id) FROM people WHERE (id = p.id OR parent_id = p.id) AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),date_of_birth)),"%Y")+0 >= '.$settings['adult-age'].') +
	dropcapchild * (SELECT COUNT(id) FROM people WHERE (id = p.id OR parent_id = p.id) AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),date_of_birth)),"%Y")+0 < '.$settings['adult-age'].')) AS maxdrops
FROM 
	people AS p, 
	transactions AS t,
	camps AS c 
WHERE 
	t.people_id = p.id AND
	NOT p.deleted AND
	c.id = p.camp_id AND 
	p.parent_id = 0 
GROUP BY p.id
	');
	
	while($row = db_fetch($result)) {
		if($row['drops']>$row['maxdrops']) {
			echo $row['firstname'].' '.$row['lastname'].' '.$row['container'].', '.$row['drops'].' drops capped to '.$row['maxdrops'].'<br />';
			db_query('INSERT INTO transactions (people_id, description, drops, transaction_date) VALUES (:id, "Drops capped to maximum", :drops, NOW())',array('id'=>$row['id'],'drops'=>-$row['drops']+$row['maxdrops']));
		}
	}