<?php

// Create array with the export_ids_people in it
$export_ids_array = explode(',', (string) $_SESSION['export_ids_stock']);
// Create a list of placeholders ? the same length as export ids given
$id_pars = str_repeat('?,', count($export_ids_array) - 1).'?';
// Put camp id as first element in the list
if ('' != $export_ids_array[0]) {
    array_unshift($export_ids_array, $_SESSION['camp']['id']);
} else {
    $export_ids_array = [$_SESSION['camp']['id']];
}
// Note for boxage: same day creation gets logged as 0 days
// related to this trello card https://trello.com/c/x3J58GgT
$data = db_query(
    'SELECT 
        boxes.*, 
        bs.label AS box_state,
        g.label AS gender, 
        p.name AS product, 
        s.label AS size, 
        l.label AS location,
        IF(DATEDIFF(now(),boxes.created) = 1, "1 day", CONCAT(DATEDIFF(now(),boxes.created), " days")) AS boxage
    FROM stock as boxes
    LEFT OUTER JOIN box_state AS bs ON bs.id = boxes.box_state_id
    LEFT OUTER JOIN products AS p ON p.id = boxes.product_id
    LEFT OUTER JOIN locations AS l ON l.id = boxes.location_id
    LEFT OUTER JOIN genders AS g ON g.id = p.gender_id
    LEFT OUTER JOIN sizes AS s ON s.id = boxes.size_id 
    WHERE l.type = "Warehouse" AND l.camp_id = ? AND (NOT boxes.deleted OR boxes.deleted IS NULL) '.($_SESSION['export_ids_stock'] ? 'AND boxes.id in ('.$id_pars.') ' : ' AND FALSE'),
    $export_ids_array
);
unset($_SESSION['export_ids_stock']);
$keys = ['box_id' => 'Box number', 'box_state' => 'Box state', 'product' => 'Product', 'gender' => 'Gender', 'size' => 'Size', 'location' => 'Location', 'boxage' => 'Age', 'items' => 'Items', 'comments' => 'Comments', 'created' => 'Created on'];

csvexport($data, 'Stock', $keys);
