<?php

$data = db_query(
    'SELECT boxes.*, g.label AS gender, p.name AS product, s.label AS size, l.label AS location
    FROM stock as boxes
    LEFT OUTER JOIN products AS p ON p.id = boxes.product_id
    LEFT OUTER JOIN locations AS l ON l.id = boxes.location_id
    LEFT OUTER JOIN genders AS g ON g.id = p.gender_id
    LEFT OUTER JOIN sizes AS s ON s.id = boxes.size_id 
    WHERE l.camp_id = :campid AND (NOT boxes.deleted OR boxes.deleted IS NULL) '.($_SESSION['export_ids_stock'] ? 'AND boxes.id in ('.$_SESSION['export_ids_stock'].')' : ' AND FALSE'),
    ['campid' => $_SESSION['camp']['id']]
);
unset($_SESSION['export_ids_stock']);
$keys = ['box_id' => 'Box number', 'product' => 'Product', 'gender' => 'Gender', 'size' => 'Size', 'location' => 'Location', 'items' => 'Items', 'comments' => 'Comments'];

csvexport($data, 'Stock', $keys);
