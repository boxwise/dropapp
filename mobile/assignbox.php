<?php

    $data['barcode'] = $_GET['assignbox'];

    // filter out the boxes belongs to thedistribution event
    $data['stock'] = db_array('
        SELECT 
            s.id, 
            CONCAT(s.box_id," - ",s.items,"x ",IFNULL(p.name,"")," ",IFNULL(g.label,""),IF(s2.label IS NULL,"",CONCAT(" (",s2.label,")"))) AS label
        FROM (stock AS s, products AS p, locations AS l)
        LEFT OUTER JOIN genders AS g ON g.id = p.gender_id
        LEFT OUTER JOIN sizes AS s2 ON s2.id = s.size_id
        WHERE 
            l.type = "Warehouse" AND 
            (NOT s.deleted OR s.deleted IS NULL) AND 
            s.box_state_id = 1 AND
            s.product_id = p.id AND 
            l.id = s.location_id AND 
            l.camp_id = '.$_SESSION['camp']['id'].' AND 
            AND l.deleted IS NULL AND 
            (s.qr_id IS NULL) ');

    $tpl->assign('include', 'mobile_assign.tpl');
