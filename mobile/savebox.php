<?php

if (!isset($_POST)) {
    redirect('?notificationFunction=genericErrorNotification');

    return;
}

    if (!intval($_POST['box_id'])) {
        $new = true;
        do {
            $_POST['box_id'] = generateBoxID();
        } while (db_value('SELECT COUNT(id) FROM stock WHERE box_id = :box_id', ['box_id' => $_POST['box_id']]));
    }

    $box = db_row('SELECT * FROM stock WHERE id = :id', ['id' => $_POST['id']]);
    if ($box && $box['location_id'] != $_POST['location_id'][0]) {
        db_query('UPDATE stock SET ordered = NULL, ordered_by = NULL, picked = NULL, picked_by = NULL WHERE id = :id', ['id' => $box['id']]);
        db_query('INSERT INTO itemsout (product_id, size_id, count, movedate, from_location, to_location) VALUES ('.$box['product_id'].','.$box['size_id'].','.$box['items'].',NOW(),'.$box['location_id'].','.$_POST['location_id'][0].')');
    }

    // Undelete a box if it is scanned
    if ($box && ('0000-00-00 00:00:00' != $box['deleted'] && !is_null($box['deleted']))) {
        db_query('UPDATE stock SET deleted = "0000-00-00 00:00" WHERE id = :id', ['id' => $_POST['id']]);
    }

    // Validate QR-code
    if (!$_POST['qr_id']) {
        redirect('?notificationFunction=genericErrorNotification');
    }

    $handler = new formHandler('stock');

    $savekeys = ['box_id', 'product_id', 'size_id', 'items', 'location_id', 'comments', 'qr_id'];
    if ($_POST['id']) {
        $savekeys[] = 'id';
    }
    $id = $handler->savePost($savekeys);

    $box = db_row('SELECT s.*, CONCAT(p.name," ",g.label) AS product, l.label AS location FROM stock AS s LEFT OUTER JOIN products AS p ON p.id = s.product_id LEFT OUTER JOIN genders AS g ON g.id = p.gender_id LEFT OUTER JOIN locations AS l ON l.id = s.location_id WHERE s.id = :id', ['id' => $id]);

    if (!$new) {
        $notificationFunction = 'editBoxNotification';
        $boxId = $box['id'];
        $boxNumber = $box['box_id'];
        $itemsCount = $box['items'];
        $boxProduct = $box['product'];
        $boxLocation = $box['location'];
    } else {
        $notificationFunction = 'saveBoxNotification';
        $boxId = $box['id'];
        $boxNumber = $box['box_id'];
        $itemsCount = $box['items'];
        $boxProduct = $box['product'];
        $boxLocation = $box['location'];
    }

    redirect('?notificationFunction='.$notificationFunction.'&boxid='.$boxId.'&boxNumber='.$boxNumber.'&itemsCount='.$itemsCount.'&boxProduct='.$boxProduct.'&boxLocation='.$boxLocation);
