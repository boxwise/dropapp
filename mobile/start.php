<?php

    $orders = db_value('SELECT COUNT(s.id) FROM stock AS s LEFT OUTER JOIN locations AS l ON s.location_id = l.id WHERE l.camp_id = :camp AND (NOT s.deleted OR s.deleted IS NULL) AND s.ordered', ['camp' => $_SESSION['camp']['id']]);
    $tpl->assign('orders', $orders);

    $tpl->assign('include', 'mobile_start.tpl');
