<?php

//Create array with the export_ids_people in it
$export_ids_array = explode(',', $_SESSION['export_ids_people']);
//Create a list of placeholders ? the same length as export ids given
$id_pars = str_repeat('?,', count($export_ids_array) - 1).'?';
//Put camp id as first element in the list
array_unshift($export_ids_array, $_SESSION['camp']['id']);

$result = db_query(
    '
	SELECT p.*, DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p.date_of_birth)), "%Y")+0 AS age
	FROM people AS p
	WHERE camp_id = ? AND (NOT deleted OR deleted IS NULL) '.($_SESSION['export_ids_people'] ? 'AND p.id in ('.$id_pars.')' : ' AND FALSE'),
    $export_ids_array
);
unset($_SESSION['export_ids_people']);
$keys = ['container' => $_SESSION['camp']['familyidentifier'], 'firstname' => 'Firstname', 'lastname' => 'Lastname', 'gender' => 'Gender', 'age' => 'Age'];

csvexport($result, 'Beneficiaries_'.$_SESSION['camp']['name'], $keys);
