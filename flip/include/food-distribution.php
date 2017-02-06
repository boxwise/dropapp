<?
	include($_SERVER['DOCUMENT_ROOT'].'/flip/lib/functions.php');

	$i = 0;
	$col = 42;
	$begin = true;

	$result = db_query('SELECT id, people.container, COUNT(*) AS number, FLOOR(COUNT(*)/3) AS green, COUNT(*)%3 AS red FROM people WHERE visible AND NOT deleted GROUP BY container ORDER BY SUBSTRING(container, 1,1), SUBSTRING(container, 2, 10)*1');
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
		$result2 = db_query('SELECT id, name FROM food WHERE visible AND NOT deleted AND (peradult > 0 OR perchild > 0) ORDER BY name');
		while($row2 = db_fetch($result2)) {
			$row['food'][$row2['name']] = db_value('SELECT CEIL(((SELECT count(id) FROM people AS p2 WHERE visible AND NOT deleted AND p2.container = p.container AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p2.date_of_birth)), "%Y")+0 >= '.$settings['adult-age'].')*f.peradult + ((SELECT count(id) FROM people AS p2 WHERE visible AND NOT deleted AND p2.container = p.container AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p2.date_of_birth)), "%Y")+0 < '.$settings['adult-age'].')*f.perchild))/f.package) AS rounded FROM people AS p LEFT OUTER JOIN food AS f ON f.id = '.$row2['id'].' WHERE p.visible AND container = "'.$row['container'].'" AND NOT p.deleted GROUP BY container ');
		}
		$list[] = $row;
		
		$result2 = db_query('SELECT CONCAT(TRIM(p.lastname),", ",TRIM(p.firstname)) AS name, DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 AS age, IF(gender="M","Male","Female") AS gender FROM people AS p WHERE visible AND NOT deleted AND container = "'.$row['container'].'" ORDER BY parent_id, DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 DESC');
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

