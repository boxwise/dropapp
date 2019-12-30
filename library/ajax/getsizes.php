<?php

    $success = true;
    $product_id = $_POST['product_id'];
    $size_id = intval($_GET['size']);

    $result = db_array('SELECT s.* FROM sizes AS s, sizegroup AS sg, products AS p WHERE s.sizegroup_id = sg.id AND p.id = :id AND p.sizegroup_id = sg.id ORDER BY s.seq', ['id' => $product_id]);

    $html = '<option></option>';

    if (count($result) == 1) {
        $html .= '<option value="'.$result[0]['id'].'" selected>'.$result[0]['label'].'</option>';
    } else {
        foreach ($result as $row) {
            $html .= '<option value="'.$row['id'].'" '.($size_id == $row['id'] ? 'selected' : '').'>'.$row['label'].'</option>';
        }
    }

    $return = ['success' => $success, 'html' => $html, 'message' => $message];
    echo json_encode($return);
