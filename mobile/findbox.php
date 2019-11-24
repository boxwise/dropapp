<?php
    $findBoxId = $_GET['findbox'];
    $barcode = db_row('
        SELECT q.code AS code, s.id AS id 
        FROM (stock AS s, locations AS l) 
        LEFT OUTER JOIN qr AS q ON q.id = s.qr_id 
        WHERE s.location_id = l.id AND box_id = :box_id', ['box_id' => $findBoxId]);

    if ($barcode['id']) {
        redirect('?boxid='.$barcode['id']);
    } else {
        redirect('?warning=1&notificationFunction=boxDoesntExistNotification');
    }
    die();
