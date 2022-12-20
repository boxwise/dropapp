<?php

$boxid = intval($_GET['move']);
// input validation
$valboxid = db_value('SELECT id FROM stock WHERE id = :id', ['id' => $boxid]);
if (!$boxid) {
    redirect('?warning=true&message=The box id is not valid');
    trigger_error('The box id is not valid', E_USER_NOTICE);
}
$newlocationid = intval($_GET['location']);
$valnewlocationid = db_value('SELECT id FROM locations WHERE id = :id', ['id' => $newlocationid]);
if (!$boxid) {
    redirect('?warning=true&message=The location id is not valid');
    trigger_error('The location id is not valid', E_USER_NOTICE);
}

[$count, $tmp, $message] = move_boxes([$valboxid], $valnewlocationid);

if ($count != 1) {
    redirect('?warning=true&message=Something went wrong! The Box was not moved.');
    trigger_error('The Box was not moved.', E_USER_NOTICE);
} else {
    redirect('?message='.$message.'.&messageAnchorText=Go back to this box&messageAnchorTarget=boxid&messageAnchorTargetValue='.$valboxid);
}
