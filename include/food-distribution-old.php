<?

	$i = 0;
	$col = 55;
	$begin = true;
	
	$result = db_query('SELECT id, 
		CONCAT(TRIM(p.lastname),", ",TRIM(p.firstname)) AS name, 
		DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 AS age, 
		IF(gender="M","Male","Female") AS gender, 
		container, 
		(SELECT COUNT(p2.id) FROM people AS p2 WHERE p2.visible AND NOT p2.deleted AND p2.parent_id = p.id)+1 AS number, 
		FLOOR(((SELECT COUNT(p2.id) FROM people AS p2 WHERE p2.visible AND NOT p2.deleted AND p2.parent_id = p.id)+1)/3) AS green, 
		((SELECT COUNT(p2.id) FROM people AS p2 WHERE p2.visible AND NOT p2.deleted AND p2.parent_id = p.id)+1)%3 AS red  
	FROM people AS p WHERE p.visible AND NOT p.deleted AND parent_id = 0 ORDER BY SUBSTRING(container, 1,1), SUBSTRING(container, 2, 10)*1');

	while($row = db_fetch($result)) {
		if($begin) {
			$row['begin'] = true; 
			$begin = false;
		} else {
			$row['begin'] = false;
		}
		$i += $row['number'];
		if($i>$col) {
			$row['newcol'] = true;
			$i=$row['number'];
		}
		$row['type'] = 'familyhead';
		$result2 = db_query('SELECT id, name FROM food WHERE visible AND (peradult > 0 OR perchild > 0) ORDER BY name');
		while($row2 = db_fetch($result2)) {
			$row['food'][$row2['name']] = db_value('SELECT CEIL(((SELECT count(id) FROM people AS p2 WHERE NOT deleted AND p2.container = p.container AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p2.date_of_birth)), "%Y")+0 >= '.$settings['adult-age'].')*f.peradult + ((SELECT count(id) FROM people AS p2 WHERE NOT deleted AND p2.container = p.container AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p2.date_of_birth)), "%Y")+0 < '.$settings['adult-age'].')*f.perchild))/f.package) AS rounded FROM people AS p LEFT OUTER JOIN food AS f ON f.id = '.$row2['id'].' WHERE p.visible AND container = "'.$row['container'].'" AND NOT p.deleted GROUP BY container ');
		}
		$list[] = $row;

		$result2 = db_query('SELECT CONCAT(TRIM(p.lastname),", ",TRIM(p.firstname)) AS name, DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 AS age, IF(gender="M","Male","Female") AS gender FROM people AS p WHERE visible AND NOT p.deleted AND parent_id = :parent_id ORDER BY DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 DESC',array('parent_id'=>$row['id']));
		while($row2 = db_fetch($result2)) {
			$row2['type'] = 'member';
			if(!$row2['age']) $row2['age'] = '?';
			$list[] = $row2;
		}
	}
	
	$cmsmain->assign('include','food-distribution.tpl');
	$cmsmain->assign('list',$list);

	// place the form elements and data in the template
	$cmsmain->assign('data',$data);
	$cmsmain->assign('formelements',$formdata);
	$cmsmain->assign('formbuttons',$formbuttons);

