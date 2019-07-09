<?

	$result = db_query('
	SELECT p.*, DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p.date_of_birth)), "%Y")+0 AS age, GROUP_CONCAT(l.name SEPARATOR ", ") AS languages
	FROM people AS p
	LEFT OUTER JOIN x_people_languages AS x ON x.people_id = p.id LEFT OUTER JOIN languages AS l ON l.id = x.language_id 
	WHERE camp_id = :campid AND NOT deleted AND parent_id = 0 
	GROUP BY p.id
	ORDER BY IF(container="AAA1",1,0), IF(container="?",1,0), SUBSTRING(REPLACE(container,"PK","Z"), 1,1), SUBSTRING(REPLACE(container,"PK","Z"), 2, 10)*1
	',array('campid'=>$_SESSION['camp']['id']));

	
	$keys = array("container","firstname","lastname","gender","age","languages");


	csvexport($result,"Beneficiaries ".$_SESSION['camp']['name'],$keys);
