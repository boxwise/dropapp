<?php

    $box = db_row('SELECT s.*, CONCAT(p.name," ",g.label) AS product, l.label AS location FROM stock AS s LEFT OUTER JOIN products AS p ON p.id = s.product_id LEFT OUTER JOIN genders AS g ON g.id = p.gender_id LEFT OUTER JOIN locations AS l ON l.id = s.location_id WHERE (NOT s.deleted OR s.deleted IS NULL) AND s.id = :box_id', ['box_id' => $_GET['saveamount']]);

    $newitems = max(0, $box['items'] - intval($_GET['items']));

    db_query('INSERT INTO history (tablename,record_id,changes,ip,changedate,user_id,from_int,to_int) VALUES ("stock",'.$box['id'].',"items","'.$_SERVER['REMOTE_ADDR'].'",NOW(),'.$_SESSION['user']['id'].', '.$box['items'].', '.$newitems.')');
    db_query('UPDATE stock SET items = :items, modified = NOW(), modified_by = :user WHERE id = :id', ['id' => $box['id'], 'items' => $newitems, 'user' => $_SESSION['user']['id']]);

    $market = db_value('SELECT id FROM locations WHERE is_market AND camp_id = :camp_id', ['camp_id' => $_SESSION['camp']['id']]);
    db_query('INSERT INTO itemsout (product_id, size_id, count, movedate, from_location, to_location) VALUES ('.$box['product_id'].','.$box['size_id'].','.intval($_GET['items']).',NOW(),'.$box['location_id'].','.$market.')');

    redirect('?message=Box <strong>'.$box['box_id'].'</strong> contains now '.$newitems.'x <strong>'.$box['product'].'</strong>.&messageAnchorText=Go back to this box&messageAnchorTarget=boxid&messageAnchorTargetValue='.$box['id']);
