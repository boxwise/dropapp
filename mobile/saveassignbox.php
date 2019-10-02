<?php

    $qrid = db_value('SELECT id FROM qr WHERE code = :barcode', ['barcode' => $_GET['saveassignbox']]);

    if (!intval($_GET['box'])) {
        trigger_error('Missing Box ID');
    }
    db_query('UPDATE stock SET qr_id = :qrid, modified = NOW(), modified_by = :user WHERE id = :boxid', ['qrid' => $qrid, 'boxid' => $_GET['box'], 'user' => $_SESSION['user']['id']]);

    $box = db_row('SELECT s.*, CONCAT(p.name," ",g.label) AS product, l.label AS location FROM stock AS s LEFT OUTER JOIN products AS p ON p.id = s.product_id LEFT OUTER JOIN genders AS g ON g.id = p.gender_id LEFT OUTER JOIN locations AS l ON l.id = s.location_id WHERE (NOT s.deleted OR s.deleted IS NULL) AND s.id = :box_id', ['box_id' => $_GET['box']]);

    db_query('INSERT INTO history (tablename,record_id,changes,ip,changedate,user_id) VALUES ("stock",'.$box['id'].',"Box assigned to QR-code '.$qrid.'","'.$_SERVER['REMOTE_ADDR'].'",NOW(),'.$_SESSION['user']['id'].')');

    redirect('?message=QR-code is succesfully linked to box '.$box['product'].' ('.$box['items'].'x) in '.$box['location']);
