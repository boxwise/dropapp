<?php

$data['men'] = db_simplearray('SELECT DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0, COUNT(id) FROM people WHERE visible AND NOT deleted AND camp_id = :camp_id AND date_of_birth IS NOT NULL AND UNIX_TIMESTAMP(date_of_birth) != 0 AND gender = "M" GROUP BY DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0', ['camp_id' => $_SESSION['camp']['id']]);

$data['women'] = db_simplearray('SELECT DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0, COUNT(id) FROM people WHERE visible AND NOT deleted AND camp_id = :camp_id AND date_of_birth IS NOT NULL AND UNIX_TIMESTAMP(date_of_birth) != 0 AND gender = "F" GROUP BY DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0', ['camp_id' => $_SESSION['camp']['id']]);

$data['oldest'] = db_value('SELECT DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 FROM people WHERE visible AND NOT deleted AND date_of_birth IS NOT NULL AND UNIX_TIMESTAMP(date_of_birth) != 0 AND camp_id = :camp_id ORDER BY date_of_birth LIMIT 1', ['camp_id' => $_SESSION['camp']['id']]) ?? 0;

if ($data['oldest'] > 0) {
    $data['oldest'] = ceil($data['oldest'] / 10) * 10;
}
$array = db_array('SELECT lastname,
(SELECT COUNT(p2.id) FROM people AS p2 WHERE p2.visible AND NOT deleted AND p2.parent_id = p.id)+1 AS size,
(SELECT COUNT(p2.id) FROM people AS p2 WHERE p2.visible AND NOT deleted AND p2.parent_id = p.id AND gender = "M" AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p2.date_of_birth)), "%Y")+0 >= '.$_SESSION['camp']['adult_age'].')+IF(p.gender="M" AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p.date_of_birth)), "%Y")+0 >= '.$_SESSION['camp']['adult_age'].',1,0) AS male,
(SELECT COUNT(p2.id) FROM people AS p2 WHERE p2.visible AND NOT deleted AND p2.parent_id = p.id AND gender = "F" AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p2.date_of_birth)), "%Y")+0 >= '.$_SESSION['camp']['adult_age'].')+IF(p.gender="F" AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p.date_of_birth)), "%Y")+0 >= '.$_SESSION['camp']['adult_age'].',1,0) AS female,
(SELECT COUNT(p2.id) FROM people AS p2 WHERE p2.visible AND NOT deleted AND p2.parent_id = p.id AND gender = "M" AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p2.date_of_birth)), "%Y")+0 < '.$_SESSION['camp']['adult_age'].')+IF(p.gender="M" AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p.date_of_birth)), "%Y")+0 < '.$_SESSION['camp']['adult_age'].',1,0) AS boys,
(SELECT COUNT(p2.id) FROM people AS p2 WHERE p2.visible AND NOT deleted AND p2.parent_id = p.id AND gender = "F" AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p2.date_of_birth)), "%Y")+0 < '.$_SESSION['camp']['adult_age'].')+IF(p.gender="F" AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p.date_of_birth)), "%Y")+0 < '.$_SESSION['camp']['adult_age'].',1,0) AS girls
FROM people AS p WHERE camp_id = :camp_id AND UNIX_TIMESTAMP(date_of_birth) != 0 AND p.visible AND parent_id IS NULL AND NOT deleted AND container != "?"', ['camp_id' => $_SESSION['camp']['id']]);
$data['familysize'] = [];
foreach ($array as $a) {
    ++$data['familysize'][$a['size']]['count'];
    $data['familysize'][$a['size']]['male'] += $a['male'];
    $data['familysize'][$a['size']]['female'] += $a['female'];
    $data['familysize'][$a['size']]['boys'] += $a['boys'];
    $data['familysize'][$a['size']]['girls'] += $a['girls'];
}
foreach ($data['familysize'] as $key => $d) {
    $data['familysize'][$key]['male'] = intval($data['familysize'][$key]['male']);
    $data['familysize'][$key]['female'] = intval($data['familysize'][$key]['female']);
    $data['familysize'][$key]['boys'] = intval($data['familysize'][$key]['boys']);
    $data['familysize'][$key]['girls'] = intval($data['familysize'][$key]['girls']);
}

ksort($data['familysize']);

$data['tip'] = db_row('SELECT * FROM tipofday ORDER BY RAND()');
$data['families'] = db_value('SELECT COUNT(id) FROM people AS p WHERE visible AND parent_id IS NULL AND NOT deleted AND p.camp_id = :camp_id', ['camp_id' => $_SESSION['camp']['id']]);
$data['residents'] = db_value('SELECT COUNT(id) FROM people WHERE visible AND NOT deleted AND camp_id = :camp_id', ['camp_id' => $_SESSION['camp']['id']]);
$data['notregistered'] = db_value('SELECT COUNT(id) FROM people WHERE visible AND NOT deleted AND camp_id = :camp_id AND notregistered', ['camp_id' => $_SESSION['camp']['id']]);
$data['residentscamp'] = db_value('SELECT COUNT(id) FROM people WHERE visible AND NOT deleted AND camp_id = :camp_id AND LEFT(container,2) != "PK"', ['camp_id' => $_SESSION['camp']['id']]);
$data['residentsoutside'] = db_value('SELECT COUNT(id) FROM people WHERE visible AND NOT deleted AND camp_id = :camp_id AND LEFT(container,2) = "PK"', ['camp_id' => $_SESSION['camp']['id']]);
$data['residents'] = db_value('SELECT COUNT(id) FROM people WHERE visible AND NOT deleted AND camp_id = :camp_id', ['camp_id' => $_SESSION['camp']['id']]);
$data['totalmen'] = db_value('SELECT COUNT(id) FROM people WHERE visible AND NOT deleted AND gender = "M" AND camp_id = :camp_id', ['camp_id' => $_SESSION['camp']['id']]);
$data['menperc'] = $data['totalmen'] / $data['residents'] * 100;
$data['totalwomen'] = db_value('SELECT COUNT(id) FROM people WHERE visible AND NOT deleted AND gender = "F" AND camp_id = :camp_id', ['camp_id' => $_SESSION['camp']['id']]);
$data['womenperc'] = $data['totalwomen'] / $data['residents'] * 100;

$data['adults'] = db_value('SELECT COUNT(id) FROM people WHERE camp_id = :camp_id AND visible AND NOT deleted AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 >= '.$_SESSION['camp']['adult_age'], ['camp_id' => $_SESSION['camp']['id']]);
$data['children'] = db_value('SELECT COUNT(id) FROM people WHERE camp_id = :camp_id AND visible AND NOT deleted AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 < '.$_SESSION['camp']['adult_age'], ['camp_id' => $_SESSION['camp']['id']]);
$data['under18'] = db_value('SELECT COUNT(id) FROM people WHERE camp_id = :camp_id AND visible AND NOT deleted AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 < 18', ['camp_id' => $_SESSION['camp']['id']]);

// open the template
$cmsmain->assign('include', 'fancygraphs.tpl');

// place the form elements and data in the template
$cmsmain->assign('data', $data);
$cmsmain->assign('formelements', $formdata);
$cmsmain->assign('formbuttons', $formbuttons);
