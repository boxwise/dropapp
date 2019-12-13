<?php

    $box = db_row('
        SELECT s.*, CONCAT(p.name," ",g.label) AS product, l.label AS location 
        FROM stock AS s 
        LEFT OUTER JOIN products AS p ON p.id = s.product_id 
        LEFT OUTER JOIN genders AS g ON g.id = p.gender_id 
        LEFT OUTER JOIN locations AS l ON l.id = s.location_id 
        WHERE (NOT s.deleted OR s.deleted IS NULL) AND s.id = :box_id', ['box_id' => $_GET['saveamount']]);

    $newitems = max(0, $box['items'] - intval($_GET['items']));

    db_query('INSERT INTO history (tablename,record_id,changes,ip,changedate,user_id,from_int,to_int) VALUES ("stock",'.$box['id'].',"items","'.$_SERVER['REMOTE_ADDR'].'",NOW(),'.$_SESSION['user']['id'].', '.$box['items'].', '.$newitems.')');
    db_query('UPDATE stock SET items = :items, modified = NOW(), modified_by = :user WHERE id = :id', ['id' => $box['id'], 'items' => $newitems, 'user' => $_SESSION['user']['id']]);

    redirect('?message=Box '.$box['box_id'].' contains now '.$newitems.'x '.$box['product'].'.&messageAnchorText=Go back to this box&messageAnchorTarget=boxid&messageAnchorTargetValue='.$box['id']);
