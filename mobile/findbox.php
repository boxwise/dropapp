<?php

    $barcode = db_row('
        SELECT q.code AS code, s.id AS id, l.type as locationType
        FROM (stock AS s, locations AS l) 
        LEFT OUTER JOIN qr AS q ON q.id = s.qr_id 
        WHERE s.location_id = l.id AND box_id = :box_id', ['box_id' => $_GET['findbox']]);

    if ('Warehouse' !== $barcode['locationType']) {
        trigger_error('The user tries to find a box belonging to a distribution event', E_USER_ERROR);

        throw new Exception('This record cannot be accessed through the dropapp. Please use boxtribute 2.0 instead', 403);
    }
    if ($barcode['id']) {
        redirect('?boxid='.$barcode['id']);
    } else {
        $message = 'This box number does not exist.';
        trigger_error($message);
        redirect('?barcode=&warning=1&message='.$message);
    }

    exit();
