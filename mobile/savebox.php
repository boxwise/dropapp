<?php

use OpenCensus\Trace\Tracer;

// Validation
if (!$_POST) {
    trigger_error('No POST data was passed to mobile savebox script.', E_USER_ERROR);
    redirect('?warning=1&message=Something went wrong! Please try again!');
}
if (!$_POST['id']) {
    trigger_error('No id data was passed to mobile savebox script.', E_USER_ERROR);
    redirect('?warning=1&message=Something went wrong! Please try again!');
}

$new = ('new' == $_POST['id']);
// Validate that an qr_id was submitted
if (!$_POST['qr_id']) {
    trigger_error('No QR-code associated to '.($new ? 'new' : 'existing').'box.', E_USER_ERROR);
    redirect('?warning=1&message=Something went wrong! Please try again!');
} else {
    // Check if other Box was already created with this QR-code
    if ($new && db_value('SELECT id FROM stock WHERE qr_id = :qrid', ['qrid' => $_POST['qr_id']])) {
        $message = 'You have already created a Box with this QR-code!';
        trigger_error($message, E_USER_ERROR);
        redirect('?warning=1&message='.$message);
    }
}
// Box creation/update
[$new, $box, $message] = db_transaction(function () use ($new) {
    $box = db_row('SELECT 
                stock.*, 
                locations.type as locationType,
                DATE_FORMAT(stock.modified,"%Y\%m\%d") AS statemodified,
                bs.id as box_state_id, 
                bs.label as box_state_name
                FROM stock
                INNER JOIN box_state bs ON bs.id = stock.box_state_id
                LEFT OUTER JOIN locations ON stock.location_id = locations.id 
                WHERE stock.id = :id', ['id' => $_POST['id']]);
    // Updates and Preparation
    if ($new) {
        Tracer::inSpan(
            ['name' => ('mobile/savebox.php:Box ID Generation')],
            function () {
                do {
                    $_POST['box_id'] = generateBoxID();
                } while (db_value('SELECT COUNT(id) FROM stock WHERE box_id = :box_id', ['box_id' => $_POST['box_id']]));
            }
        );
    } else {
        $is_scrap = (!empty($_POST['scrap']));
        $is_lost = (!empty($_POST['lost']));

        $id = $_POST['id'];

        // Getting the new box state id based on the location
        $newboxstate = db_row('SELECT bs.id as box_state_id, bs.label as box_state_name FROM locations l INNER JOIN box_state bs ON bs.id = l.box_state_id WHERE l.id = :id', ['id' => $_POST['location_id'][0]]);

        //  when checked scrap or lost in the form
        if ($is_scrap) {
            $newboxstate['box_state_id'] = 6;
            $newboxstate['box_state_name'] = 'Scrap';
        } elseif ($is_lost) {
            $newboxstate['box_state_id'] = 2;
            $newboxstate['box_state_name'] = 'Lost';
        }

        if ($box['location_id'] != $_POST['location_id'] && !$is_scrap && !$is_lost) {
            // Boxes should not be relocated to virtual locations
            // Related to https://trello.com/c/Ci74t1Wj
            if (in_array($newboxstate['box_state_name'], ['Lost', 'Scrap'])) {
                $_POST['location_id'] = $box['location_id'];
                if ('Lost' == $newboxstate['box_state_name']) {
                    $is_lost = true;
                } elseif ('Scrap' == $newboxstate['box_state_name']) {
                    $is_scrap = true;
                }
            } else {
                db_query('UPDATE stock SET ordered = NULL, ordered_by = NULL, picked = NULL, picked_by = NULL WHERE id = :id', ['id' => $box['id']]);
                db_query('INSERT INTO itemsout (product_id, size_id, count, movedate, from_location, to_location) VALUES ('.$box['product_id'].','.$box['size_id'].','.$box['items'].',NOW(),'.$box['location_id'].','.$_POST['location_id'].')');
            }
        }

        // Update the box state if the state changes
        if ($newboxstate['box_state_id'] != $box['box_state_id']) {
            db_query('UPDATE stock SET box_state_id = :box_state_id, modified = NOW() WHERE id = :id', [':box_state_id' => $newboxstate['box_state_id'],  'id' => $id]);
            simpleSaveChangeHistory('stock', $box['id'], 'changed box state from '.$box['box_state_name'].' to '.$newboxstate['box_state_name']);
        }

        // Undelete a box if it is scanned
        if ('0000-00-00 00:00:00' != $box['deleted'] && !is_null($box['deleted'])) {
            db_query('UPDATE stock SET deleted = "0000-00-00 00:00" WHERE id = :id', ['id' => $_POST['id']]);
        }

        // Tracker if box is moved from one base to another
        $old_base = db_row(
            'SELECT 
                            o.label as organisation, b.name as base, b.id as base_id
                        FROM 
                            stock s
                        LEFT JOIN 
                            locations l ON l.id=s.location_id
                        LEFT JOIN 
                            camps b ON b.id =l.camp_id
                        LEFT JOIN 
                            organisations o ON o.id=b.organisation_id
                        WHERE
                            s.id = :id',
            ['id' => $_POST['id']]
        );
        $new_base = ['organisation' => $_SESSION['organisation']['label'], 'base' => $_SESSION['camp']['name'], 'base_id' => $_SESSION['camp']['id']];
        if ($old_base != $new_base) {
            $message = 'Box moved from '.($old_base['organisation'] != $new_base['organisation'] ? 'organisation '.$old_base['organisation'].' to organisation '.$new_base['organisation'] : 'base '.$old_base['base'].' to base '.$new_base['base'].' of organisation '.$new_base['organisation']);
            simpleSaveChangeHistory('stock', $box['id'], $message);
            trigger_error($message);
        }
    }

    if (!$is_lost && !$is_scrap) {
        // keys of POST to be saved
        $savekeys = ['box_id', 'product_id', 'size_id', 'items', 'location_id', 'comments', 'qr_id'];

        if (!$new) {
            $savekeys[] = 'id';
        }

        $handler = new formHandler('stock');

        $id = $handler->savePost($savekeys);
        // related trello https://trello.com/c/XjNwO3sL
        // add remove tagsand insert the new tags when creating the new box
        db_query('DELETE FROM tags_relations WHERE object_id = :stock_id AND object_type = "Stock"', [':stock_id' => $id]);

        if ($_POST['tags']) {
            $query = 'INSERT IGNORE INTO tags_relations (tag_id, object_type, `object_id`) VALUES ';

            $params = [];
            $tags = $_POST['tags'];
            for ($i = 0; $i < sizeof($tags); ++$i) {
                $query .= "(:tag_id{$i}, 'Stock', :stock_id)";
                $params = array_merge($params, ['tag_id'.$i => $tags[$i]]);
                if ($i !== sizeof($tags) - 1) {
                    $query .= ',';
                }
            }

            $params = array_merge($params, ['stock_id' => $id]);
            db_query($query, $params);
        }
    }
    // Log qr box connection in history table
    if ($new) {
        simpleSaveChangeHistory('qr', $_POST['qr_id'], 'QR code associated to box.', [], ['int' => $id]);
    }
    $box = db_row('SELECT s.*, CONCAT(p.name," ",g.label) AS product, l.label AS location FROM stock AS s LEFT OUTER JOIN products AS p ON p.id = s.product_id LEFT OUTER JOIN genders AS g ON g.id = p.gender_id LEFT OUTER JOIN locations AS l ON l.id = s.location_id WHERE s.id = :id', ['id' => $id]);

    return [$new, $box, $message];
});

// Validate QR-code again
if (!$box['qr_id']) {
    trigger_error('No QR-code associated to '.($new ? 'new' : 'existing').' box after fromhandler.', E_USER_ERROR);
}

if (!$new) {
    $message = 'Box '.$box['box_id'].' modified with '.$box['product'].' ('.$box['items'].'x) in '.$box['location'].'. <a href="?boxid='.$box['id'].'">Go back to this box.</a>';
} else {
    $message = 'New box with box ID <strong class="bigger">'.$box['box_id'].'</strong> (write this number on the box label). This box contains '.$box['items'].' '.$box['product'].' and is located in '.$box['location'].'. <a href="?boxid='.$box['id'].'">Edit this box.</a>';
}

redirect('?boxid='.$box['id'].'&message='.$message);
