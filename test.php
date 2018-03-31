<?php
	require('library/core.php');
	
	$result = db_query('SELECT 
	p.id,  
	IF((SELECT COUNT(id) FROM people WHERE volunteer AND (id = p.id OR parent_id = p.id)),99999,dropcapadult * (SELECT COUNT(id) FROM people WHERE (id = p.id OR parent_id = p.id) AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),date_of_birth)),"%Y")+0 >= 13) +
	dropcapchild * (SELECT COUNT(id) FROM people WHERE (id = p.id OR parent_id = p.id) AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),date_of_birth)),"%Y")+0 < 13)) AS maxdrops
FROM 
	people AS p, 
	camps AS c 
WHERE 
	NOT deleted AND
	c.id = p.camp_id AND 
	p.parent_id = 0
	');
	
	while($row = db_fetch($result)) {
		echo $row['id'].' ';
	}