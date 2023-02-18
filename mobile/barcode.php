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
                        l.type as locationType,
                        GROUP_CONCAT(tags.label ORDER BY tags.seq SEPARATOR 0x1D) AS taglabels,
                        GROUP_CONCAT(tags.color ORDER BY tags.seq) AS tagcolors,
                        DATE_FORMAT(s.modified,"%Y/%m/%d") AS statemodified,
                        bs.id as stateid, 
                        bs.label as statelabel
                    FROM stock AS s
                        INNER JOIN box_state AS bs ON bs.id = s.box_state_id
                        LEFT OUTER JOIN products AS p ON p.id = s.product_id
                        LEFT OUTER JOIN genders AS g ON g.id = p.gender_id
                        LEFT OUTER JOIN sizes AS s2 ON s2.id = s.size_id
                        LEFT OUTER JOIN locations AS l ON l.id = s.location_id
                        LEFT OUTER JOIN qr AS q ON q.id = s.qr_id
                        LEFT OUTER JOIN camps AS c ON c.id = l.camp_id
                        LEFT OUTER JOIN tags_relations ON tags_relations.object_id = s.id AND tags_relations.object_type = "Stock"
                        LEFT OUTER JOIN tags ON tags.id = tags_relations.tag_id AND tags_relations.object_type = "Stock" AND tags.deleted IS NULL
                    WHERE s.box_id = :box_id', ['box_id' => $_GET['boxid']]);

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
                        l.type as locationType,
                        GROUP_CONCAT(tags.label ORDER BY tags.seq SEPARATOR 0x1D) AS taglabels,
                        GROUP_CONCAT(tags.color ORDER BY tags.seq) AS tagcolors,
                        DATE_FORMAT(s.modified,"%Y/%m/%d") AS statemodified,
                        bs.id as stateid, 
                        bs.label as statelabel
                    FROM stock AS s
                    INNER JOIN box_state AS bs ON bs.id = s.box_state_id
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
    redirect('?editbox='.$box['id'].'&preference=classic');
} elseif ($box['camp_id'] != $_SESSION['camp']['id'] && $box['campname']) {
    // Box is registered in a different camp
    trigger_error('Scanned box is registered to another base.');
    redirect('?editbox='.$box['id']
        .'&preference=classic'
        .'&warning=true&message=Oops!! This box is registered in '.$box['campname'].', are you sure this is what you were looking for?<br /><br /> No? <a href="/mobile.php">Search again!</a><br /><br /> Yes? Edit and save this box to transfer it to '.$_SESSION['camp']['name'].'.');
} elseif ($box['id']) {
    // Box is not deleted and belongs to your base
    // box is not empty
    mobile_distro_check($box['locationType']);

    // Forward to new Boxtribute App
    $message = v2_forward($settings['v2_base_url'], '/boxes/'.$box['box_id']);
    $data['message'] = (!isset($data['message']) ? $message : $data['message']);

    // disable Box if in Lost or Scrap state
    $box['disabled'] = in_array($box['statelabel'], ['Lost', 'Scrap']);

    $locations = db_array('SELECT 
                            l.id AS value,
                            IF(l.box_state_id <> 1, concat(l.label, " - Boxes are <i>",bs.label,"</i>"),l.label) as label,
                            IF(l.box_state_id <> 1, concat("The Box State will be changed to ",bs.label),NULL) as tooltip,
                            IF(l.id = :location, true, false) AS selected 
                        FROM locations AS l
                        INNER JOIN box_state AS bs ON bs.id = l.box_state_id
                        WHERE l.deleted IS NULL AND camp_id = :camp_id AND l.type = "Warehouse" 
                        ORDER BY l.seq', ['camp_id' => $_SESSION['camp']['id'], 'location' => $box['location_id']]);
    $history = showHistory('stock', $box['id']);
    $tpl->assign('box', $box);
    $tpl->assign('history', $history);
    $tpl->assign('locations', $locations);
    $tpl->assign('include', 'mobile_scan.tpl');

    // include orders on the bottom
    $orders = db_value('SELECT COUNT(s.id) FROM stock AS s LEFT OUTER JOIN locations AS l ON s.location_id = l.id WHERE l.camp_id = :camp AND l.type = "Warehouse" AND (NOT s.deleted OR s.deleted IS NULL) AND s.ordered', ['camp' => $_SESSION['camp']['id']]);
    $tpl->assign('orders', $orders);
} else {
    // Test to figure out QR bug
    if ($_GET['barcode'] && db_row('SELECT s.* FROM stock s LEFT JOIN qr ON qr.id=s.qr_id WHERE qr.code= :code', ['code' => $_GET['barcode']])) {
        trigger_error('Existing box forwarded to new box.', E_USER_ERROR);
        redirect('?warning=1&message=Something went wrong! Please try again!');
    }

    // no box was loaded --> newbox
    redirect('?newbox='.$data['barcode'].(isset($_GET['qrlegacy']) ? '&qrlegacy=1' : ''));
}
