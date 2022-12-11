<?php

$data['barcode'] = $_GET['barcode'];

if ($_GET['barcode'] && !db_value('SELECT id FROM qr WHERE code = :code AND legacy = :legacy', ['code' => $_GET['barcode'], 'legacy' => (isset($_GET['qrlegacy']) ? 1 : 0)])) {
    // There is a barcode hash in the url, but this hash is not in the qr table
    $message = 'This is not a valid QR-code for '.$_SESSION['organisation']['label'];
    // Check if it is a legacy error
    if (db_value('SELECT id FROM qr WHERE code = :code AND legacy = :legacy', ['code' => $_GET['barcode'], 'legacy' => (!isset($_GET['qrlegacy']) ? 1 : 0)])) {
        trigger_error('Scanned QR-code exist in qr-table, but with different legacy value!');
    } else {
        trigger_error($message);
    }
    redirect('?warning=1&message='.$message);
} elseif ($_GET['boxid']) {
    // Load box data
    // a boxid was passed through the url

    // add tags in query to display the tags' label near the title
    // related trello https://trello.com/c/XjNwO3sL
    $box = db_row('SELECT 
                        s.*, 
                        c.id AS camp_id, 
                        c.name AS campname, 
                        CONCAT(p.name," ",g.label," ",IFNULL(s2.label, "")) AS product, 
                        p.name AS product2, g.label AS gender, 
                        IFNULL(s2.label, "") AS size, 
                        l.label AS location,
                        GROUP_CONCAT(tags.label ORDER BY tags.seq SEPARATOR 0x1D) AS taglabels,
                        GROUP_CONCAT(tags.color ORDER BY tags.seq) AS tagcolors,
                        l.type as locationType
                    FROM stock AS s
                        LEFT OUTER JOIN products AS p ON p.id = s.product_id
                        LEFT OUTER JOIN genders AS g ON g.id = p.gender_id
                        LEFT OUTER JOIN sizes AS s2 ON s2.id = s.size_id
                        LEFT OUTER JOIN locations AS l ON l.id = s.location_id
                        LEFT OUTER JOIN qr AS q ON q.id = s.qr_id
                        LEFT OUTER JOIN camps AS c ON c.id = l.camp_id
                        LEFT OUTER JOIN tags_relations ON tags_relations.object_id = s.id AND tags_relations.object_type = "Stock"
                        LEFT OUTER JOIN tags ON tags.id = tags_relations.tag_id AND tags_relations.object_type = "Stock" AND tags.deleted IS NULL
                    WHERE s.id = :id', ['id' => $_GET['boxid']]);

    if ($box['taglabels']) {
        $taglabels = explode(chr(0x1D), $box['taglabels']);
        $tagcolors = explode(',', $box['tagcolors']);
        foreach ($taglabels as $tagkey => $taglabel) {
            $data['tags'][$tagkey] = ['label' => $taglabel, 'color' => $tagcolors[$tagkey], 'textcolor' => get_text_color($tagcolors[$tagkey])];
        }
    }
} elseif ($_GET['barcode']) {
    // a barcode hash was passed in the url and it exits in qr-table
    $qr_id = db_value('SELECT id FROM qr WHERE code = :code AND legacy = :legacy', ['code' => $_GET['barcode'], 'legacy' => (isset($_GET['qrlegacy']) ? 1 : 0)]);
    $box = db_row('SELECT 
                        s.*, 
                        c.id AS camp_id, 
                        c.name AS campname, 
                        CONCAT(p.name," ",g.label," ",IFNULL(s2.label, "")) AS product, 
                        p.name AS product2, 
                        g.label AS gender, 
                        IFNULL(s2.label, "") AS size, 
                        l.label AS location,
                        GROUP_CONCAT(tags.label ORDER BY tags.seq SEPARATOR 0x1D) AS taglabels,
                        GROUP_CONCAT(tags.color ORDER BY tags.seq) AS tagcolors, 
                        l.type as locationType 
                    FROM stock AS s
                    LEFT OUTER JOIN products AS p ON p.id = s.product_id
                    LEFT OUTER JOIN genders AS g ON g.id = p.gender_id
                    LEFT OUTER JOIN sizes AS s2 ON s2.id = s.size_id
                    LEFT OUTER JOIN locations AS l ON l.id = s.location_id
                    LEFT OUTER JOIN qr AS q ON q.id = s.qr_id
                    LEFT OUTER JOIN camps AS c ON c.id = l.camp_id
                    LEFT OUTER JOIN tags_relations ON tags_relations.object_id = s.id AND tags_relations.object_type = "Stock"
                    LEFT OUTER JOIN tags ON tags.id = tags_relations.tag_id AND tags_relations.object_type = "Stock" AND tags.deleted IS NULL
                    WHERE q.id = :qrid', ['qrid' => $qr_id]);
    if ($box['taglabels']) {
        $taglabels = explode(chr(0x1D), $box['taglabels']);
        $tagcolors = explode(',', $box['tagcolors']);
        foreach ($taglabels as $tagkey => $taglabel) {
            $data['tags'][$tagkey] = ['label' => $taglabel, 'color' => $tagcolors[$tagkey], 'textcolor' => get_text_color($tagcolors[$tagkey])];
        }
    }
} else {
    trigger_error('No barcode or box ID passed.', E_USER_ERROR);
    redirect('?warning=1&message=Something went wrong! Please try again!');
}

// Box data is loaded
if ('0000-00-00 00:00:00' != $box['deleted'] && !is_null($box['deleted'])) {
    // Box is a deleted box
    trigger_error('Scanned box is a deleted box.');
    redirect('?editbox='.$box['id']);
} elseif ($box['camp_id'] != $_SESSION['camp']['id'] && $box['campname']) {
    // Box is registered in a different camp
    trigger_error('Scanned box is registered to another base.');
    redirect('?editbox='.$box['id'].'&warning=true&message=Oops!! This box is registered in '.$box['campname'].', are you sure this is what you were looking for?<br /><br /> No? <a href="/mobile.php">Search again!</a><br /><br /> Yes? Edit and save this box to transfer it to '.$_SESSION['camp']['name'].'.');
} elseif ($box['id']) {
    // Box is not deleted and belongs to your base
    // box is not empty
    mobile_distro_check($box['locationType']);

    $data['message'] = v2_forward($settings['v2_base_url'], '/boxes/'.$box['box_id']);

    $orders = db_value('SELECT COUNT(s.id) FROM stock AS s LEFT OUTER JOIN locations AS l ON s.location_id = l.id WHERE l.camp_id = :camp AND l.type = "Warehouse" AND (NOT s.deleted OR s.deleted IS NULL) AND s.ordered', ['camp' => $_SESSION['camp']['id']]);
    $tpl->assign('orders', $orders);

    $locations = db_array('SELECT id AS value, label, IF(id = :location, true, false) AS selected FROM locations WHERE deleted IS NULL AND camp_id = :camp_id AND type = "Warehouse" ORDER BY seq', ['camp_id' => $_SESSION['camp']['id'], 'location' => $box['location_id']]);
    $history = showHistory('stock', $box['id']);
    $tpl->assign('box', $box);
    $tpl->assign('history', $history);
    $tpl->assign('locations', $locations);
    $tpl->assign('include', 'mobile_scan.tpl');
} else {
    // Test to figure out QR bug
    if ($_GET['barcode'] && db_row('SELECT s.* FROM stock s LEFT JOIN qr ON qr.id=s.qr_id WHERE qr.code= :code', ['code' => $_GET['barcode']])) {
        trigger_error('Existing box forwarded to new box.', E_USER_ERROR);
        redirect('?warning=1&message=Something went wrong! Please try again!');
    }

    // no box was loaded --> newbox
    redirect('?newbox='.$data['barcode'].(isset($_GET['qrlegacy']) ? '&qrlegacy=1' : ''));
}
