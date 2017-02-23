<?php

	$data['items'] = intval(db_value('SELECT SUM(items) FROM (stock AS s, products AS p) LEFT OUTER JOIN locations AS l ON s.location_id = l.id WHERE s.product_id = p.id AND NOT p.deleted AND NOT s.deleted AND l.visible AND l.camp_id = :camp_id',array('camp_id'=>$_SESSION['camp']['id'])));
	$data['boxes'] = db_value('SELECT COUNT(s.id) FROM (stock AS s, products AS p) LEFT OUTER JOIN locations AS l ON s.location_id = l.id WHERE s.product_id = p.id AND NOT p.deleted AND NOT s.deleted AND l.visible AND l.camp_id = :camp_id',array('camp_id'=>$_SESSION['camp']['id']));

	if($_SESSION['camp']['market']) {
		$data['tip'] = db_row('SELECT * FROM tipofday ORDER BY RAND()');
		$data['families'] = db_value('SELECT COUNT(id) FROM people AS p WHERE visible AND parent_id = 0 AND NOT deleted AND p.camp_id = :camp_id',array('camp_id'=>$_SESSION['camp']['id']));
		$data['residents'] = db_value('SELECT COUNT(id) FROM people WHERE visible AND NOT deleted AND camp_id = :camp_id',array('camp_id'=>$_SESSION['camp']['id']));
		$data['totalmen'] = db_value('SELECT COUNT(id) FROM people WHERE visible AND NOT deleted AND gender = "M" AND camp_id = :camp_id',array('camp_id'=>$_SESSION['camp']['id']));
		$data['menperc'] = $data['totalmen']/$data['residents']*100;
		$data['totalwomen'] = db_value('SELECT COUNT(id) FROM people WHERE visible AND NOT deleted AND gender = "F" AND camp_id = :camp_id',array('camp_id'=>$_SESSION['camp']['id']));
		$data['womenperc'] = $data['totalwomen']/$data['residents']*100;
	
		$data['containers'] = db_value('SELECT COUNT(DISTINCT(container)) FROM people WHERE visible AND camp_id = :camp_id',array('camp_id'=>$_SESSION['camp']['id']));
	
		$data['adults'] = db_value('SELECT COUNT(id) FROM people WHERE visible AND camp_id = :camp_id AND NOT deleted AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 >= '.$settings['adult-age'],array('camp_id'=>$_SESSION['camp']['id']));
		$data['children'] = db_value('SELECT COUNT(id) FROM people WHERE visible AND camp_id = :camp_id AND NOT deleted AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 < '.$settings['adult-age'],array('camp_id'=>$_SESSION['camp']['id']));
		$data['under18'] = db_value('SELECT COUNT(id) FROM people WHERE visible AND camp_id = :camp_id AND NOT deleted AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 < 18',array('camp_id'=>$_SESSION['camp']['id']));
	
		$data['sold'] = db_value('SELECT SUM(count) FROM transactions AS t, people AS p WHERE t.people_id = p.id AND p.camp_id = :camp_id',array('camp_id'=>$_SESSION['camp']['id']));
		$data['marketdays'] = db_value('SELECT COUNT(DISTINCT(DATE_FORMAT(transaction_date,"%d-%m-%Y"))) FROM transactions AS t, people AS p WHERE t.people_id = p.id AND p.camp_id = :camp_id',array('camp_id'=>$_SESSION['camp']['id']));
		$popular = db_row('SELECT SUM(count) AS count, CONCAT(p.name," ", g.label) AS product FROM transactions AS t, products AS p, genders AS g WHERE g.id = p.gender_id AND p.id = t.product_id AND p.camp_id = :camp_id GROUP BY product_id ORDER BY SUM(count) DESC LIMIT 1',array('camp_id'=>$_SESSION['camp']['id']));
		$data['popularcount'] = intval($popular['count']);
		$data['popularname'] = $popular['product']?$popular['product']:'none';
	
		$date = strftime('%Y-%m-%d',strtotime('-14 days'));
		$end = strftime('%Y-%m-%d');
	
		while (strtotime($date) <= strtotime($end)) {
	        $sales = db_value('SELECT COUNT(t.id) FROM transactions AS t, people AS p WHERE t.people_id = p.id AND p.camp_id = :camp_id AND t.product_id > 0 AND DATE_FORMAT(t.transaction_date,"%Y-%m-%d") = :date',array('date'=>$date,'camp_id'=>$_SESSION['camp']['id']));
	
			if($sales) {
				$data['sales'][strftime("%e %b",strtotime($date))] = db_value('SELECT SUM(t.count) AS aantal FROM (transactions AS t, people AS pp)
	LEFT OUTER JOIN products AS p ON t.product_id = p.id
	WHERE t.people_id = pp.id AND pp.camp_id = :camp_id AND t.product_id > 0 AND t.transaction_date >= "'.$date.' 00:00" AND t.transaction_date <= "'.$date.' 23:59"',array('camp_id'=>$_SESSION['camp']['id']));
			}
	        $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
		}
	
		// open the template
		$cmsmain->assign('include','start-market.tpl');
	
		// place the form elements and data in the template
	} elseif($_SESSION['camp']['id']==2) {
		$cmsmain->assign('include','start-nomarket.tpl');
	}

	$cmsmain->assign('data',$data);
