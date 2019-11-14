<?php

$result = db_query(
    '
	SELECT p.*, DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p.date_of_birth)), "%Y")+0 AS age
	FROM people AS p
	WHERE camp_id = :campid AND (NOT deleted OR deleted IS NULL) '.($_SESSION['export_ids_people'] ? 'AND p.id in ('.$_SESSION['export_ids_people'].')' : ' AND FALSE'),
    ['campid' => $_SESSION['camp']['id']]
);
unset($_SESSION['export_ids_people']);
$keys = ['container' => $_SESSION['camp']['familyidentifier'], 'firstname' => 'Firstname', 'lastname' => 'Lastname', 'gender' => 'Gender', 'age' => 'Age'];

csvexport($result, 'Beneficiaries_'.$_SESSION['camp']['name'], $keys);
