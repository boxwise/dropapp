<?php

	$data['items'] = db_value('SELECT SUM(items) FROM (stock AS s, products AS p) LEFT OUTER JOIN locations AS l ON s.location_id = l.id WHERE s.product_id = p.id AND NOT p.deleted AND NOT s.deleted AND l.visible AND l.camp_id = :camp_id',array('camp_id'=>$_SESSION['camp']['id']));
	$data['boxes'] = db_value('SELECT COUNT(s.id) FROM (stock AS s, products AS p) LEFT OUTER JOIN locations AS l ON s.location_id = l.id WHERE s.product_id = p.id AND NOT p.deleted AND NOT s.deleted AND l.visible AND l.camp_id = :camp_id',array('camp_id'=>$_SESSION['camp']['id']));

	if($_SESSION['camp']['id']==1) {
		$data['tip'] = db_row('SELECT * FROM tipofday ORDER BY RAND()');
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
	
		$data['sold'] = db_value('SELECT SUM(count) FROM transactions');
		$data['marketdays'] = db_value('SELECT COUNT(DISTINCT(DATE_FORMAT(transaction_date,"%d-%m-%Y"))) FROM transactions');
		$popular = db_row('SELECT SUM(count) AS count, CONCAT(p.name," ", g.label) AS product FROM transactions AS t, products AS p, genders AS g WHERE g.id = p.gender_id AND p.id = t.product_id GROUP BY product_id ORDER BY SUM(count) DESC LIMIT 1');
		$data['popularcount'] = $popular['count'];
		$data['popularname'] = $popular['product'];
	
		$date = strftime('%Y-%m-%d',strtotime('-14 days'));
		$end = strftime('%Y-%m-%d');
	
		while (strtotime($date) <= strtotime($end)) {
	        $sales = db_value('SELECT COUNT(id) FROM transactions AS t WHERE t.product_id > 0 AND DATE_FORMAT(t.transaction_date,"%Y-%m-%d") = :date',array('date'=>$date));
	
			if($sales) {
				$data['sales'][strftime("%e %b",strtotime($date))] = db_value('SELECT SUM(t.count) AS aantal FROM transactions AS t
	LEFT OUTER JOIN products AS p ON t.product_id = p.id
	WHERE t.product_id > 0 AND t.transaction_date >= "'.$date.' 00:00" AND t.transaction_date <= "'.$date.' 23:59"');
			}
	        $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
		}
	
		// open the template
		$cmsmain->assign('include','start-neakavala.tpl');
	
		// place the form elements and data in the template
	} elseif($_SESSION['camp']['id']==2) {
		$cmsmain->assign('include','start-chios.tpl');
	}

	$cmsmain->assign('data',$data);
