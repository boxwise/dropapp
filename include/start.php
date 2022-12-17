<?php

if (isset($_SESSION['camp']['id'])) {
    $data['items'] = intval(db_value('SELECT SUM(items) FROM (stock AS s, products AS p) LEFT OUTER JOIN locations AS l ON s.location_id = l.id WHERE s.product_id = p.id AND (NOT p.deleted OR p.deleted IS NULL) AND (NOT s.deleted OR s.deleted IS NULL) AND l.visible AND l.camp_id = :camp_id AND l.type = "Warehouse" ', ['camp_id' => $_SESSION['camp']['id']]));
    $data['boxes'] = db_value('SELECT COUNT(s.id) FROM (stock AS s, products AS p) LEFT OUTER JOIN locations AS l ON s.location_id = l.id WHERE s.product_id = p.id AND (NOT p.deleted OR p.deleted IS NULL) AND (NOT s.deleted OR s.deleted IS NULL) AND l.visible AND l.camp_id = :camp_id AND l.type = "Warehouse" ', ['camp_id' => $_SESSION['camp']['id']]);
    $data['warehouse'] = db_value('SELECT id FROM locations WHERE locations.camp_id = '.intval($_SESSION['camp']['id']).' AND locations.type = "Warehouse" LIMIT 1 ');
    $data['tip'] = db_row('SELECT * FROM tipofday ORDER BY RAND()');
    $data['families'] = db_value('SELECT COUNT(id) FROM people AS p WHERE visible AND parent_id IS NULL AND NOT deleted AND p.camp_id = :camp_id', ['camp_id' => $_SESSION['camp']['id']]);
    $data['residents'] = db_value('SELECT COUNT(id) FROM people WHERE visible AND NOT deleted AND camp_id = :camp_id', ['camp_id' => $_SESSION['camp']['id']]);
    $data['notregistered'] = db_value('SELECT COUNT(id) FROM people WHERE visible AND NOT deleted AND camp_id = :camp_id AND notregistered', ['camp_id' => $_SESSION['camp']['id']]);
    $data['residentsoutside'] = db_value('SELECT COUNT(id) FROM people WHERE visible AND NOT deleted AND camp_id = :camp_id AND LEFT(container,2) = "PK"', ['camp_id' => $_SESSION['camp']['id']]);
    $data['residents'] = db_value('SELECT COUNT(id) FROM people WHERE visible AND NOT deleted AND camp_id = :camp_id', ['camp_id' => $_SESSION['camp']['id']]);
    $data['totalmen'] = db_value('SELECT COUNT(id) FROM people WHERE visible AND NOT deleted AND gender = "M" AND camp_id = :camp_id', ['camp_id' => $_SESSION['camp']['id']]);
    $data['totalwomen'] = db_value('SELECT COUNT(id) FROM people WHERE visible AND NOT deleted AND gender = "F" AND camp_id = :camp_id', ['camp_id' => $_SESSION['camp']['id']]);
    $data['children'] = db_value('SELECT COUNT(id) FROM people WHERE visible AND camp_id = :camp_id AND NOT deleted AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 < '.$_SESSION['camp']['adult_age'], ['camp_id' => $_SESSION['camp']['id']]);
    $data['under18'] = db_value('SELECT COUNT(id) FROM people WHERE visible AND camp_id = :camp_id AND NOT deleted AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 < 18', ['camp_id' => $_SESSION['camp']['id']]);

    if ('0' != $data['residents']) {
        $data['menperc'] = $data['totalmen'] / $data['residents'] * 100;
        $data['womenperc'] = $data['totalwomen'] / $data['residents'] * 100;
        $data['childrenprcnt'] = $data['children'] / $data['residents'] * 100;
        $data['under18prcnt'] = $data['under18'] / $data['residents'] * 100;
    } else {
        $data['menperc'] = $data['womenperc'] = $data['childrenprcnt'] = $data['under18prcnt'] = 0;
    }

    $data['adults'] = db_value('SELECT COUNT(id) FROM people WHERE visible AND camp_id = :camp_id AND NOT deleted AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 >= '.$_SESSION['camp']['adult_age'], ['camp_id' => $_SESSION['camp']['id']]);
    $data['sold'] = db_value('SELECT SUM(count) FROM transactions AS t, people AS p WHERE t.people_id = p.id AND p.camp_id = :camp_id', ['camp_id' => $_SESSION['camp']['id']]);
    $data['marketdays'] = db_value('SELECT COUNT(DISTINCT(DATE_FORMAT(transaction_date,"%d-%m-%Y"))) FROM transactions AS t, people AS p WHERE t.people_id = p.id AND p.camp_id = :camp_id', ['camp_id' => $_SESSION['camp']['id']]);
    $data['bank'] = db_value('SELECT SUM(drops) FROM people AS p, transactions AS t WHERE p.camp_id = :camp_id AND t.people_id = p.id ', ['camp_id' => $_SESSION['camp']['id']]);

    $popular = db_row('SELECT SUM(count) AS count, CONCAT(p.name," ", g.label) AS product FROM transactions AS t, products AS p, genders AS g WHERE g.id = p.gender_id AND p.id = t.product_id AND p.camp_id = :camp_id GROUP BY product_id ORDER BY SUM(count) DESC LIMIT 1', ['camp_id' => $_SESSION['camp']['id']]);
    $data['popularcount'] = intval($popular['count']);
    $data['popularname'] = $popular['product'] ? $popular['product'] : 'none';

    $date = strftime('%Y-%m-%d', strtotime('-21 days'));
    $end = strftime('%Y-%m-%d');

    while (strtotime($date) <= strtotime($end)) {
        $sales = db_value('SELECT COUNT(t.id) FROM transactions AS t, people AS p WHERE t.people_id = p.id AND p.camp_id = :camp_id AND t.product_id > 0 AND DATE_FORMAT(t.transaction_date,"%Y-%m-%d") = :date', ['date' => $date, 'camp_id' => $_SESSION['camp']['id']]);
        if ($sales) {
            $data['sales'][strftime('%a %e %b', strtotime($date))] = db_value('SELECT SUM(t.count) AS aantal FROM (transactions AS t, people AS pp)
LEFT OUTER JOIN products AS p ON t.product_id = p.id
WHERE t.people_id = pp.id AND pp.camp_id = :camp_id AND t.product_id > 0 AND t.transaction_date >= "'.$date.' 00:00" AND t.transaction_date <= "'.$date.' 23:59"', ['camp_id' => $_SESSION['camp']['id']]);
        }

        $result = db_query('SELECT c.label, p.gender, 
		
SUM(ROUND(time_to_sec((TIMEDIFF((SELECT b2.transaction_date FROM borrow_transactions AS b2 WHERE b1.bicycle_id = b2.bicycle_id AND b1.people_id = b2.people_id AND b2.status = "in" AND b2.transaction_date > b1.transaction_date ORDER BY b2.transaction_date ASC LIMIT 1), b1.transaction_date))) / 60)) AS time,

COUNT(b1.id) AS count 

FROM borrow_transactions AS b1 LEFT OUTER JOIN borrow_items AS i ON i.id = b1.bicycle_id LEFT OUTER JOIN borrow_categories AS c ON c.id = i.category_id LEFT OUTER JOIN people AS p ON p.id = b1.people_id WHERE b1.status = "out" AND DATE_FORMAT(b1.transaction_date,"%Y-%m-%d") = :date GROUP BY p.gender, c.id', ['date' => $date]);

        while ($borrow = db_fetch($result)) {
            $borrow['label'] .= ' '.$borrow['gender'];
            $data['borrow'][strftime('%a %e %b', strtotime($date))][$borrow['label']] = $borrow;
        }
        $date = date('Y-m-d', strtotime('+1 day', strtotime($date)));
    }

    $data['weeklabel'] = (strftime('%w') ? 'Last' : 'This');
    $previous_week = strtotime($data['weeklabel'].' week');
    $start_week = date('Y-m-d', strtotime('monday', $previous_week));
    $end_week = date('Y-m-d', strtotime('sunday', $previous_week));

    $data['newpeople'] = db_value('SELECT COUNT(id) FROM people WHERE NOT deleted AND created >= "'.$start_week.'" AND created <= "'.$end_week.'" AND camp_id = '.intval($_SESSION['camp']['id'])." AND LEFT(container,2) != 'PK'");

    $data['newcardsM'] = db_value('SELECT COUNT(p.id) FROM history AS h LEFT OUTER JOIN people AS p ON p.id = h.record_id WHERE changedate >= "'.$start_week.'" AND changedate <= "'.$end_week.'" AND tablename = "people" AND changes = "bicycletraining" AND p.gender = "M"');
    $data['newcardsF'] = db_value('SELECT COUNT(p.id) FROM history AS h LEFT OUTER JOIN people AS p ON p.id = h.record_id WHERE changedate >= "'.$start_week.'" AND changedate <= "'.$end_week.'" AND tablename = "people" AND changes = "bicycletraining" AND p.gender = "F"');

    $data['totalcardsM'] = db_value('SELECT COUNT(id) FROM people WHERE NOT deleted AND bicycletraining AND gender = "M"');
    $data['totalcardsF'] = db_value('SELECT COUNT(id) FROM people WHERE NOT deleted AND bicycletraining AND gender = "F"');
    $data['cardsM'] = intval(100 * $data['totalcardsM'] / db_value('SELECT COUNT(id) FROM people WHERE DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 >= 15 AND gender = "M" AND NOT deleted AND LEFT(container,2) != "PK" AND camp_id = 1'));
    $data['cardsF'] = intval(100 * $data['totalcardsF'] / db_value('SELECT COUNT(id) FROM people WHERE DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 >= 15 AND gender = "F" AND NOT deleted AND LEFT(container,2) != "PK" AND camp_id = 1'));

    $data['newbrcardsM'] = db_value('SELECT COUNT(p.id) FROM history AS h LEFT OUTER JOIN people AS p ON p.id = h.record_id WHERE changedate >= "'.$start_week.'" AND changedate <= "'.$end_week.'" AND tablename = "people" AND changes = "workshoptraining" AND p.gender = "M"');
    $data['newbrcardsF'] = db_value('SELECT COUNT(p.id) FROM history AS h LEFT OUTER JOIN people AS p ON p.id = h.record_id WHERE changedate >= "'.$start_week.'" AND changedate <= "'.$end_week.'" AND tablename = "people" AND changes = "workshoptraining" AND p.gender = "F"');

    $data['totalbrcardsM'] = db_value('SELECT COUNT(id) FROM people WHERE NOT deleted AND workshoptraining AND gender = "M"');
    $data['totalbrcardsF'] = db_value('SELECT COUNT(id) FROM people WHERE NOT deleted AND workshoptraining AND gender = "F"');
    $data['brcardsM'] = intval(100 * $data['totalbrcardsM'] / db_value('SELECT COUNT(id) FROM people WHERE DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 >= 15 AND gender = "M" AND NOT deleted AND LEFT(container,2) != "PK" AND camp_id = 1'));
    $data['brcardsF'] = intval(100 * $data['totalbrcardsF'] / db_value('SELECT COUNT(id) FROM people WHERE DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 >= 15 AND gender = "F" AND NOT deleted AND LEFT(container,2) != "PK" AND camp_id = 1'));

    $data['laundry_appointments'] = db_value('SELECT COUNT(id) FROM laundry_appointments WHERE people_id > 0 AND cyclestart = :cyclestart', ['cyclestart' => $_SESSION['camp']['laundry_cyclestart']]);
    $data['laundry_slots'] = db_value('SELECT COUNT(id) FROM laundry_slots');
    $data['laundry_noshow'] = db_value('SELECT COUNT(id) FROM laundry_appointments WHERE cyclestart = :cyclestart AND noshow', ['cyclestart' => $_SESSION['camp']['laundry_cyclestart']]);
    $result = db_query('SELECT (SELECT COUNT(id) FROM people WHERE id = people_id OR parent_id = people_id) AS c FROM laundry_appointments WHERE people_id > 0 AND cyclestart = :cyclestart GROUP BY people_id', ['cyclestart' => $_SESSION['camp']['laundry_cyclestart']]);
    while ($row = db_fetch($result)) {
        $data['laundry_beneficiaries'] += $row['c'];
    }

    $previous = strftime('%Y-%m-%d', strtotime('-14 days', strtotime($_SESSION['camp']['laundry_cyclestart'])));

    $data['laundry_prev_appointments'] = db_value('SELECT COUNT(id) FROM laundry_appointments WHERE people_id > 0 AND cyclestart = :cyclestart', ['cyclestart' => $previous]);
    $data['laundry_prev_noshow'] = db_value('SELECT COUNT(id) FROM laundry_appointments WHERE cyclestart = :cyclestart AND noshow', ['cyclestart' => $previous]);
    $result = db_query('SELECT (SELECT COUNT(id) FROM people WHERE id = people_id OR parent_id = people_id) AS c FROM laundry_appointments WHERE people_id > 0 AND cyclestart = :cyclestart GROUP BY people_id', ['cyclestart' => $previous]);
    while ($row = db_fetch($result)) {
        $data['laundry_prev_beneficiaries'] += $row['c'];
    }

    // open the template

    // place the form elements and data in the template
    $cmsmain->assign('data', $data);

    $cmsmain->assign('include', 'start-market.tpl');
}
