<?php

$move = intval($_GET['move']);

[$box, $message] = db_transaction(function () use ($move) {
    $box = db_row('
        SELECT 
            s.*, 
            CONCAT(p.name," ",g.label) AS product, 
            l.label AS location, 
            s.location_id AS location_id, 
            l.type as locationType,
            bs.label AS statelabel,
            bs.id as stateid
        FROM stock AS s
        INNER JOIN box_state AS bs ON bs.id = s.box_state_id 
        LEFT OUTER JOIN products AS p ON p.id = s.product_id 
        LEFT OUTER JOIN genders AS g ON g.id = p.gender_id 
        LEFT OUTER JOIN locations AS l ON l.id = s.location_id 
        WHERE (NOT s.deleted OR s.deleted IS NULL) AND s.id = :id', ['id' => $move]);

    // validate box id
    if (!$box) {
        redirect('?message=The box id is not valid');
        trigger_error('The box id is not valid', E_USER_NOTICE);
    }

    $newlocation = db_row('SELECT 
                                l.*,
                                bs.label AS statelabel,
                                bs.id as stateid
                           FROM locations AS l
                           INNER JOIN box_state AS bs ON bs.id = l.box_state_id 
                           WHERE l.id = :location AND l.type = "Warehouse"', ['location' => intval($_GET['location'])]);

    mobile_distro_check($box['locationType']);
    $message = 'Box '.$box['box_id'].' contains '.$box['items'].'x '.$box['product'];
    $is_moved = false;
    // Boxes should not be relocated to virtual locations
    // related to https://trello.com/c/Ci74t1Wj
    if (!in_array($newlocation['statelabel'], ['Lost', 'Scrap'])) {
        $message .= ' is moved from '.$box['location'].' to '.$newlocation['label'];
        $is_moved = true;
        db_query('UPDATE stock SET location_id = :location_id, modified = NOW(), modified_by = :user, ordered = NULL, ordered_by = NULL, picked = NULL, picked_by = NULL WHERE id = :id', ['location_id' => $newlocation['id'], 'id' => $box['id'], 'user' => $_SESSION['user']['id']]);
        // @todo: use simpleSaveChangeHistory / addition of IP address support simpleSaveChangeHistory
        db_query('INSERT INTO history (tablename,record_id,changes,ip,changedate,user_id,from_int,to_int) VALUES ("stock",'.$box['id'].', "location_id", "'.$_SERVER['REMOTE_ADDR'].'",NOW(),'.$_SESSION['user']['id'].', '.$box['location_id'].', '.$newlocation['id'].')');
    }

    if ($box['location_id'] != $newlocation['id']) {
        db_query('INSERT INTO itemsout (product_id, size_id, count, movedate, from_location, to_location) VALUES (:product_id, :size_id, :count, NOW(), :from_location, :to_location)', ['product_id' => $box['product_id'], 'size_id' => $box['size_id'], 'count' => $box['items'], 'from_location' => $box['location_id'], 'to_location' => $newlocation['id']]);
    }

    // Update the box state if the state changes
    if ($newlocation['stateid'] != $box['stateid']) {
        $message .= ($is_moved) ? ', and ' : ' ';
        $message .= 'state changed from '.$box['statelabel'].' to '.$newlocation['statelabel'];
        db_query('UPDATE stock SET box_state_id = :box_state_id, modified = NOW(), modified_by = :user_id WHERE id = :id', ['box_state_id' => $newlocation['stateid'],  'id' => $box['id'], 'user_id' => $_SESSION['user']['id']]);
        simpleSaveChangeHistory('stock', $box['id'], 'changed box state from '.$box['statelabel'].' to '.$newlocation['statelabel']);
    }

    return [$box, $message];
});

redirect('?message='.$message.'.&messageAnchorText=Go back to this box&messageAnchorTarget=boxid&messageAnchorTargetValue='.$box['id']);
