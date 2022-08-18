<?php

// Show new box state onchange of the location
$id = $_POST['id'];
$success = true;

if ($id) {
    $result = db_row('SELECT 
                         l.label AS location, bs.label AS box_state
                      FROM
                         locations l
                            INNER JOIN
                         box_state bs ON bs.id = l.box_state_id
                       WHERE l.id = :location_id AND bs.id <> 1', ['location_id' => $id]);

    $newBoxState = $result['box_state'];

    $message = $newBoxState ? sprintf(' -> <span style="color:blue">%s</span>', $newBoxState) : '';
    $success = false;
    $redirect = false;
}

$return = ['success' => $success, 'message' => $message, 'redirect' => $redirect];

echo json_encode($return);
