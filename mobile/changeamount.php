<?php

    $box = db_row('
        SELECT s.*, CONCAT(p.name," ",g.label) AS product, l.label AS location, l.type as locationType
        FROM stock AS s 
        LEFT OUTER JOIN products AS p ON p.id = s.product_id 
        LEFT OUTER JOIN genders AS g ON g.id = p.gender_id 
        LEFT OUTER JOIN locations AS l ON l.id = s.location_id 
        WHERE s.id = :id', ['id' => $_GET['changeamount']]);

    if ('Warehouse' !== $box['locationType']) {
        trigger_error('The user tries to change amount on a box belonging to a distribution event', E_USER_ERROR);

        throw new Exception('This record cannot be accessed through the dropapp. Please use boxtribute 2.0 instead', 403);
    }

    $tpl->assign('box', $box);
    $tpl->assign('include', 'mobile_amount.tpl');
