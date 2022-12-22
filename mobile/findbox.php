<?php

    $barcode = db_row('
        SELECT q.code AS code, s.id AS id, s.box_id AS box_id, l.type as locationType
        FROM (stock AS s, locations AS l) 
        LEFT OUTER JOIN qr AS q ON q.id = s.qr_id 
        WHERE s.location_id = l.id AND box_id = :box_id', ['box_id' => $_GET['findbox']]);

    if ($barcode['id']) {
        mobile_distro_check($barcode['locationType']);
        redirect('?boxid='.$barcode['box_id']);
    } else {
        $message = 'This box number does not exist.';
        trigger_error($message);
        redirect('?barcode=&warning=1&message='.$message);
    }

    exit();
