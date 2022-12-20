<?php

// related to https://trello.com/c/Ci74t1Wj
// Show new box state onchange of the location
$id = $_POST['id'];
$success = true;

if ($id) {
    $result = db_row('SELECT 
                         l.label AS location, 
                         bs.label AS box_state,
                         bs.id as box_state_id
                      FROM
                         locations l
                            INNER JOIN
                         box_state bs ON bs.id = l.box_state_id
                       WHERE l.id = :location_id', ['location_id' => $id]);

    $newBoxState = $result['box_state'];
    $newBoxStateId = $result['box_state_id'];

    $message = [
        'box_state' => $newBoxState ? sprintf(' &rarr; <span style="color:blue">%s</span>', $newBoxState) : '',
        'box_state_id' => $newBoxStateId,
    ];
    $success = false;
    $redirect = false;
}

$return = ['success' => $success, 'message' => $message, 'redirect' => $redirect];

echo json_encode($return);
