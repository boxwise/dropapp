<?php

    $box = db_row('
        SELECT 
            s.*, 
            CONCAT(p.name," ",g.label) AS product, 
            l.label AS location, l.type as locationType,
            bs.label AS statelabel,
            bs.id as stateid
        FROM stock AS s 
        INNER JOIN box_state AS bs ON bs.id = s.box_state_id
        LEFT OUTER JOIN products AS p ON p.id = s.product_id 
        LEFT OUTER JOIN genders AS g ON g.id = p.gender_id 
        LEFT OUTER JOIN locations AS l ON l.id = s.location_id 
        WHERE s.id = :id', ['id' => $_GET['changeamount']]);

    mobile_distro_check($box['locationType']);
    $box['disabled'] = in_array($box['statelabel'], ['Lost', 'Scrap']);
    $tpl->assign('box', $box);
    $tpl->assign('include', 'mobile_amount.tpl');
