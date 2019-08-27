<?php

    //a Box gets picked
    if ($_GET['picked']) {
        db_query('UPDATE stock SET ordered = NULL, ordered_by = NULL, picked = NOW(), picked_by = :user WHERE id = :id', ['id' => intval($_GET['picked']), 'user' => $_SESSION['user']['id']]);
        simpleSaveChangeHistory('stock', $_GET['picked'], 'Box picked from warehouse ');
    }
    //a box is lost
    if ($_GET['lost']) {
        $from['int'] = db_value('SELECT location_id FROM stock WHERE id = :id', ['id' => intval($_GET['lost'])]);
        $to['int'] = db_value('SELECT id FROM locations WHERE camp_id = :camp AND is_lost = 1 LIMIT 1', ['camp' => $_SESSION['camp']['id']]);
        db_query('UPDATE stock SET location_id = :loc, ordered = NULL, ordered_by = NULL, modified = NOW(), modified_by = :user WHERE id = :id', ['loc' => $to['int'], 'id' => intval($_GET['lost']), 'user' => $_SESSION['user']['id']]);
        simpleSaveChangeHistory('stock', $_GET['lost'], 'location_id', $from, $to);
    }

    $boxes = db_array('
SELECT s.id, l.label AS location, s.box_id, p.name AS product, s.items, si.label AS size, g.label AS gender FROM stock AS s 
LEFT OUTER JOIN locations AS l ON s.location_id = l.id
LEFT OUTER JOIN products AS p ON s.product_id = p.id
LEFT OUTER JOIN genders AS g ON p.gender_id = g.id
LEFT OUTER JOIN sizes AS si ON s.size_id = si.id
WHERE l.camp_id = :camp AND (NOT s.deleted OR s.deleted IS NULL) AND s.ordered
ORDER BY l.id, s.box_id', ['camp' => $_SESSION['camp']['id']]);

    $tpl->assign('boxes', $boxes);

    $tpl->assign('include', 'mobile_vieworders.tpl');
