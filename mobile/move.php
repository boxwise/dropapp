<?php

$id = intval($_GET['move']);
// input validation
$valId = db_value('SELECT id FROM stock WHERE id = :id', ['id' => $id]);
if (!$valId) {
    redirect('?warning=true&message=The box id is not valid');
    trigger_error('The box id is not valid', E_USER_NOTICE);
}
$newlocationid = intval($_GET['location']);
$valnewlocationid = db_value('SELECT id FROM locations WHERE id = :id', ['id' => $newlocationid]);
if (!$valnewlocationid) {
    redirect('?warning=true&message=The location id is not valid');
    trigger_error('The location id is not valid', E_USER_NOTICE);
}

[$count, $tmp, $message] = move_boxes([$valId], $valnewlocationid);

if (1 != $count) {
    redirect('?warning=true&message=Something went wrong! The Box was not moved.');
    trigger_error('The Box was not moved.', E_USER_NOTICE);
} else {
    $boxId = db_value('SELECT box_id FROM stock WHERE id = :id', ['id' => $valId]);
    redirect('?message='.$message.'.&messageAnchorText=Go back to this box&messageAnchorTarget=boxid&messageAnchorTargetValue='.$boxId);
}
