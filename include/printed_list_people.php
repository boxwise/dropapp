<?php

    $i = 0;
    $col = 35;
    $begin = true;

    $result = db_query('SELECT id, people.container, COUNT(*) AS number, FLOOR((COUNT(*) + SUM(extraportion))/3) AS green, (COUNT(*) + SUM(extraportion))%3 AS red, SUM(extraportion) AS extra FROM people WHERE visible AND camp_id = '.$_SESSION['camp']['id'].' AND NOT deleted GROUP BY container ORDER BY SUBSTRING(REPLACE(container,"PK","Z"), 1,1), SUBSTRING(REPLACE(container,"PK","Z"), 2, 10)*1');
    while ($row = db_fetch($result)) {
        if ($begin) {
            $row['begin'] = true;
            $begin = false;
        } else {
            $row['begin'] = false;
        }
        $i += $row['number'];
        if ($i > $col) {
            $row['newcol'] = true;
            $i = $row['number'];
        }
        $row['type'] = 'container';
        $list[] = $row;

        $result2 = db_query('SELECT p.parent_id, CONCAT(TRIM(p.lastname),", ",TRIM(p.firstname)) AS name, DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 AS age, IF(gender="M","Male","Female") AS gender, extraportion AS extra FROM people AS p WHERE visible AND camp_id = '.$_SESSION['camp']['id'].' AND NOT deleted AND container = "'.$row['container'].'" ORDER BY parent_id, DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 DESC');
        while ($row2 = db_fetch($result2)) {
            $row2['type'] = ($row2['parent_id']) ? 'member' : 'familyhead';
            if (!$row2['age']) {
                $row2['age'] = '?';
            }
            $list[] = $row2;
        }
    }

    if ($_GET['export']) {
        header('Content-Type: application/csv;charset=UTF-8');
        header('Content-Disposition: attachment; filename=vegetable-list.csv');
        header('Pragma: no-cache');

        foreach ($list as $l) {
            if ($l['id']) {
                echo '"'.trim($l['container']).'","'.$l['number'].' people ('.($l['green'] ? $l['green'].' '.$translate['bag_for_one'] : '').($l['green'] && $l['red'] ? ', ' : '').($l['red'] ? $l['red'].' '.$translate['bag_for_three'] : '').')'."\"\n";
            } else {
                echo '"","'.trim($l['name']).'",'.$l['age'].','.$l['gender']."\n";
            }
        }

        exit();
    }
        $cmsmain->assign('include', 'printed_list_people.tpl');
        $cmsmain->assign('list', $list);

        // place the form elements and data in the template
        $cmsmain->assign('data', $data);
        $cmsmain->assign('formelements', $formdata);
        $cmsmain->assign('formbuttons', $formbuttons);
