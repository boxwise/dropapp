<?

	$data['men'] = db_simplearray('SELECT DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0, COUNT(id) FROM people WHERE visible AND NOT deleted AND date_of_birth IS NOT NULL AND gender = "M" GROUP BY DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0');
	

	$data['women'] = db_simplearray('SELECT DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0, COUNT(id) FROM people WHERE visible AND NOT deleted AND date_of_birth IS NOT NULL AND gender = "F" GROUP BY DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0');
	
	$data['oldest'] = db_value('SELECT DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 FROM people WHERE visible AND date_of_birth IS NOT NULL ORDER BY date_of_birth LIMIT 1');
	
	$array = db_array('SELECT lastname,
(SELECT COUNT(p2.id) FROM people AS p2 WHERE p2.visible AND NOT deleted AND p2.parent_id = p.id)+1 AS size, 
(SELECT COUNT(p2.id) FROM people AS p2 WHERE p2.visible AND NOT deleted AND p2.parent_id = p.id AND gender = "M" AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p2.date_of_birth)), "%Y")+0 >= 13)+IF(p.gender="M" AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p.date_of_birth)), "%Y")+0 >= 13,1,0) AS male,
(SELECT COUNT(p2.id) FROM people AS p2 WHERE p2.visible AND NOT deleted AND p2.parent_id = p.id AND gender = "F" AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p2.date_of_birth)), "%Y")+0 >= 13)+IF(p.gender="F" AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p.date_of_birth)), "%Y")+0 >= 13,1,0) AS female,
(SELECT COUNT(p2.id) FROM people AS p2 WHERE p2.visible AND NOT deleted AND p2.parent_id = p.id AND gender = "M" AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p2.date_of_birth)), "%Y")+0 < 13)+IF(p.gender="M" AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p.date_of_birth)), "%Y")+0 < 13,1,0) AS boys,
(SELECT COUNT(p2.id) FROM people AS p2 WHERE p2.visible AND NOT deleted AND p2.parent_id = p.id AND gender = "F" AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p2.date_of_birth)), "%Y")+0 < 13)+IF(p.gender="F" AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p.date_of_birth)), "%Y")+0 < 13,1,0) AS girls
FROM people AS p WHERE p.visible AND parent_id = 0 AND NOT deleted AND container != "?"');
	$data['familysize'] = array();
	foreach($array as $a) {
		$data['familysize'][$a['size']]['count'] ++;
		$data['familysize'][$a['size']]['male'] += $a['male'];
		$data['familysize'][$a['size']]['female'] += $a['female'];
		$data['familysize'][$a['size']]['boys'] += $a['boys'];
		$data['familysize'][$a['size']]['girls'] += $a['girls'];
	}
	foreach($data['familysize'] as $key=>$d) {
		$data['familysize'][$key]['male'] = intval($data['familysize'][$key]['male']);
		$data['familysize'][$key]['female'] = intval($data['familysize'][$key]['female']);
		$data['familysize'][$key]['boys'] = intval($data['familysize'][$key]['boys']);
		$data['familysize'][$key]['girls'] = intval($data['familysize'][$key]['girls']);
	}


	ksort($data['familysize']);

	$data['families'] = db_value('SELECT COUNT(id) FROM people AS p WHERE visible AND parent_id = 0 AND NOT deleted');
	$data['residents'] = db_value('SELECT COUNT(id) FROM people WHERE visible AND NOT deleted');
	$data['totalmen'] = db_value('SELECT COUNT(id) FROM people WHERE visible AND NOT deleted AND gender = "M"');
	$data['menperc'] = $data['totalmen']/$data['residents']*100;
	$data['totalwomen'] = db_value('SELECT COUNT(id) FROM people WHERE visible AND NOT deleted AND gender = "F"');
	$data['womenperc'] = $data['totalwomen']/$data['residents']*100;

	$data['containers'] = db_value('SELECT COUNT(DISTINCT(container)) FROM people WHERE visible');
	
	$data['adults'] = db_value('SELECT COUNT(id) FROM people WHERE visible AND NOT deleted AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 >= '.$settings['adult-age']);
	$data['children'] = db_value('SELECT COUNT(id) FROM people WHERE visible AND NOT deleted AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 < '.$settings['adult-age']);
	$data['under18'] = db_value('SELECT COUNT(id) FROM people WHERE visible AND NOT deleted AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 < 18');
	
	// open the template
	$cmsmain->assign('include','fancygraphs.tpl');
			
	// place the form elements and data in the template
	$cmsmain->assign('data',$data);
	$cmsmain->assign('formelements',$formdata);
	$cmsmain->assign('formbuttons',$formbuttons);
	
		
