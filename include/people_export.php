<?php

$variables = [];
$id_pars = '';

if ('all' === $_SESSION['export_ids_people']) {
    // just export all beneficiaries
    $variables = [$_SESSION['camp']['id']];
} else {
    // Create array with the export_ids_people in it
    $variables = explode(',', (string) $_SESSION['export_ids_people']);
    // Create a list of placeholders ? the same length as export ids given
    $id_pars = str_repeat('?,', count($variables) - 1).'?';
    // Put camp id as first element in the list
    if ('' != $variables[0]) {
        array_unshift($variables, $_SESSION['camp']['id']);
    } else {
        $variables = [$_SESSION['camp']['id']];
    }
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
    WHERE p.camp_id = ? AND (NOT p.deleted OR p.deleted IS NULL) ';

// Conditionally add export ids or export all
$query .= ('all' !== $_SESSION['export_ids_people'] && $id_pars ? 'AND p.id IN ('.$id_pars.')' : '');

$result = db_query($query, $variables);
unset($_SESSION['export_ids_people']);

$keys = [
    'container' => $_SESSION['camp']['familyidentifier'],
    'firstname' => 'Firstname',
    'lastname' => 'Surname',
    'gender' => 'Gender',
    'age' => 'Age',
    'tags' => 'Tags',
    'comments' => 'Comments',
    'boxtribute_family_id' => 'Boxtribute Family ID',
    'familyhead' => 'Head of Family',
];

csvexport($result, 'Beneficiaries_'.$_SESSION['camp']['name'], $keys);
