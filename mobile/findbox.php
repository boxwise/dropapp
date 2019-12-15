<?php

    $barcode = db_row('
        SELECT q.code AS code, s.id AS id 
        FROM (stock AS s, locations AS l) 
        LEFT OUTER JOIN qr AS q ON q.id = s.qr_id 
        WHERE s.location_id = l.id AND box_id = :box_id', ['box_id' => $_GET['findbox']]);

    if ($barcode['id']) {
        redirect('?boxid='.$barcode['id']);
    } else {
        $message = 'This box number does not exist.';
        trigger_error($message);
        redirect('?barcode=&warning=1&message='.$message);
    }
    die();
