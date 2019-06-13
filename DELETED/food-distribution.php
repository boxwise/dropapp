<?php

	$i = 0;
	$col = 35;
	$begin = true;

	$result = db_query('
		SELECT id, people.container, COUNT(*) AS number, SUM(extraportion) AS extra, 
			SUM(IF((DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0) < '.$_SESSION['camp']['adult_age'].', 0, 1)) AS adults, 
			SUM(IF((DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0) < 3, 1, 0)) AS baby, 
			SUM(IF((DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0) < '.$_SESSION['camp']['adult_age'].' AND NOT (DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0) < 3, 1, 0)) AS children
		FROM people 
		WHERE visible AND camp_id = '.$_SESSION['camp']['id'].' AND NOT deleted 
		GROUP BY container 
		ORDER BY SUBSTRING(REPLACE(container,"PK","Z"),1,1), SUBSTRING(REPLACE(container,"PK","Z"), 2, 10)*1');
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
		$row['type'] = 'container';
		$result2 = db_query('SELECT id, name FROM food WHERE visible AND NOT deleted AND (peradult > 0 OR perchild > 0) ORDER BY name');
		while($row2 = db_fetch($result2)) {
			$row['food'][$row2['name']] = db_value('SELECT CEIL(((SELECT count(id) 
					FROM people AS p2 
					WHERE visible AND camp_id = '.$_SESSION['camp']['id'].' AND NOT deleted AND p2.container = p.container AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p2.date_of_birth)), "%Y")+0 >= '.$_SESSION['camp']['adult_age'].')*f.peradult + 
					((SELECT count(id) 
					FROM people AS p2 
					WHERE visible AND NOT deleted AND p2.container = p.container AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p2.date_of_birth)), "%Y")+0 < '.$_SESSION['camp']['adult_age'].')*f.perchild))/f.package) AS rounded 
				FROM people AS p 
				LEFT OUTER JOIN food AS f ON f.id = '.$row2['id'].' 
				WHERE p.visible AND container = "'.$row['container'].'" AND NOT p.deleted 
				GROUP BY container ');
		}
		$list[] = $row;

		$result2 = db_query('SELECT p.parent_id, CONCAT(TRIM(p.lastname),", ",TRIM(p.firstname)) AS name, DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 AS age, IF(gender="M","Male","Female") AS gender, extraportion AS extra 
			FROM people AS p 
			WHERE visible AND camp_id = '.$_SESSION['camp']['id'].' AND NOT deleted AND container = "'.$row['container'].'" 
			ORDER BY parent_id, DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 DESC');
		while($row2 = db_fetch($result2)) {
			$row2['type'] = ($row2['parent_id'])?'member':'familyhead';
			#if(!$row2['age']) $row2['age'] = '?';
			$list[] = $row2;
		}
	}


		$cmsmain->assign('include','food-distribution.tpl');
		$cmsmain->assign('list',$list);

		// place the form elements and data in the template
		$cmsmain->assign('data',$data);
		$cmsmain->assign('formelements',$formdata);
		$cmsmain->assign('formbuttons',$formbuttons);
