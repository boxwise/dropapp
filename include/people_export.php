<?php

// Create array with the export_ids_people in it
$export_ids_array = explode(',', (string) $_SESSION['export_ids_people']);
// Create a list of placeholders ? the same length as export ids given
$id_pars = str_repeat('?,', count($export_ids_array) - 1).'?';
// Put camp id as first element in the list
if ('' != $export_ids_array[0]) {
    array_unshift($export_ids_array, $_SESSION['camp']['id']);
} else {
    $export_ids_array = [$_SESSION['camp']['id']];
}

$query = '
    SELECT 
        p.*, 
        DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p.date_of_birth)), "%Y")+0 AS age, 
        IF(ISNULL(p.parent_id), p.id, p.parent_id) AS boxtribute_family_id, 
        IF(ISNULL(p.parent_id), TRUE, FALSE) AS familyhead,
        (
            SELECT GROUP_CONCAT(t.label) 
            FROM tags t 
            INNER JOIN tags_relations tr ON t.id = tr.tag_id 
            WHERE tr.object_type = "People" AND tr.object_id = p.id
        ) AS tags
    FROM people AS p
    WHERE camp_id = ? AND (NOT deleted OR deleted IS NULL) ';

// Conditionally add export ids or export all
// Related to https://trello.com/c/TYgsqCRH
$query .= ($_SESSION['export_ids_people'] && false == $_SESSION['export_all_people'] && $id_pars ? 'AND p.id IN ('.$id_pars.')' : ' ');

$result = db_query(
    $query,
    false == $_SESSION['export_all_people'] ? $export_ids_array : [$export_ids_array[0]]
);
unset($_SESSION['export_ids_people'], $_SESSION['export_all_people']);

$keys = [
    'container' => $_SESSION['camp']['familyidentifier'],
    'firstname' => 'Firstname',
    'lastname' => 'Surname',
    'gender' => 'Gender',
    'age' => 'Age',
    'comments' => 'Comments',
    'boxtribute_family_id' => 'Boxtribute Family ID',
    'familyhead' => 'Head of Family',
    'tags' => 'Tags',
];

csvexport($result, 'Beneficiaries_'.$_SESSION['camp']['name'], $keys);
