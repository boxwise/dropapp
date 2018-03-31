<?

	header("Content-type: text/csv");
	header("Content-Disposition: attachment; filename=names.csv");
	header("Pragma: no-cache");
	header("Expires: 0");

	$table = 'borrow_items';
	$ajax = checkajax();
	if(!DEFINED('CORE')) include('core.php');

	$result = db_query('
	SELECT p.*, DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p.date_of_birth)), "%Y")+0 AS age, GROUP_CONCAT(l.name SEPARATOR ", ") AS languages
	FROM people AS p
	LEFT OUTER JOIN x_people_languages AS x ON x.people_id = p.id LEFT OUTER JOIN languages AS l ON l.id = x.language_id 
	WHERE camp_id = :campid AND NOT deleted AND parent_id = 0 
	GROUP BY p.id
	ORDER BY IF(container="AAA1",1,0), IF(container="?",1,0), SUBSTRING(REPLACE(container,"PK","Z"), 1,1), SUBSTRING(REPLACE(container,"PK","Z"), 2, 10)*1
	',array('campid'=>$_SESSION['camp']['id']));

	echo '"Container";"Firstname";"Lastname";"Gender";"Age";"Language"'."\n";
	
	while($f = db_fetch($result)) {
		echo '"'.$f['container'].'";"'.$f['firstname'].'";"'.$f['lastname'].'";"'.$f['gender'].'";"'.$f['age'].'";"'.$f['languages'].'"'."\n";
		
		$result2 = db_query('SELECT *, DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p.date_of_birth)), "%Y")+0 AS age, GROUP_CONCAT(l.name SEPARATOR ", ") AS languages 
		FROM people AS p
		LEFT OUTER JOIN x_people_languages AS x ON x.people_id = p.id LEFT OUTER JOIN languages AS l ON l.id = x.language_id 
		WHERE camp_id = :campid AND NOT deleted AND parent_id = :parent_id 
		GROUP BY p.id
		ORDER BY date_of_birth DESC',array('campid'=>$_SESSION['camp']['id'],'parent_id'=>$f['id']));
		while($s = db_fetch($result2)) {
			echo '"";"'.$s['firstname'].'";"'.$s['lastname'].'";"'.$s['gender'].'";"'.$f['age'].'";"'.$f['languages'].'"'."\n";
		}
	}
	die();