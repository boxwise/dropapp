<?

$result = db_query('
	SELECT p.*, DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p.date_of_birth)), "%Y")+0 AS age
	FROM people AS p
	WHERE camp_id = :campid AND NOT deleted', array('campid' => $_SESSION['camp']['id']));
$keys = array("container" => $_SESSION['camp']['familyidentifier'], "firstname" => "Firstname", "lastname" => 'Lastname', "gender" => "Gender", "age" => "Age");

csvexport($result, "Beneficiaries " . $_SESSION['camp']['name'], $keys);
