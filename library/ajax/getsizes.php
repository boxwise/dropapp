<?php

$success = true;
$product_id = $_POST['product_id'];
$size_id = intval($_GET['size']);

$result = db_array('SELECT s.id, s.label FROM sizes AS s JOIN sizes_sizegroup AS ss ON ss.size_id = s.id JOIN products AS p ON p.sizegroup_id = ss.sizegroup_id AND p.id = :id ORDER BY ss.seq', ['id' => $product_id]);
$html = '<option></option>';

if (1 == count($result)) {
    $html .= '<option value="'.$result[0]['id'].'" selected>'.$result[0]['label'].'</option>';
} else {
    foreach ($result as $row) {
        $html .= '<option value="'.$row['id'].'" '.($size_id == $row['id'] ? 'selected' : '').'>'.$row['label'].'</option>';
    }
}

$return = ['success' => $success, 'html' => $html, 'message' => $message];
echo json_encode($return);
