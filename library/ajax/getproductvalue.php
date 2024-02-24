<?php

$success = true;
$product_id = $_POST['product_id'];

$drops = db_value('SELECT value FROM products WHERE id = :id', ['id' => $_POST['product_id']]);
$return = ['success' => $success, 'drops' => $drops, 'message' => $message];
echo json_encode($return);
