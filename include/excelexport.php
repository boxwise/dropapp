<?

	header("Content-type: text/csv");
	header("Content-Disposition: attachment; filename=names.csv");
	header("Pragma: no-cache");
	header("Expires: 0");

	$table = 'borrow_items';
	$ajax = checkajax();
	if(!DEFINED('CORE')) include('core.php');

	$result = db_query('SELECT * FROM people WHERE camp_id = :campid AND NOT deleted AND parent_id = 0 ORDER BY IF(container="AAA1",1,0), IF(container="?",1,0), SUBSTRING(REPLACE(container,"PK","Z"), 1,1), SUBSTRING(REPLACE(container,"PK","Z"), 2, 10)*1',array('campid'=>$_SESSION['camp']['id']));

	echo '"Container";"Firstname";"Lastname"'."\n";
	
	while($f = db_fetch($result)) {
		echo '"'.$f['container'].'";"'.$f['firstname'].'";"'.$f['lastname'].'";"'.$f['gender'].'";"'.strftime('%d-%m-%Y',strtotime($f['date_of_birth'])).'"'."\n";
		
		$result2 = db_query('SELECT * FROM people WHERE camp_id = :campid AND NOT deleted AND parent_id = :parent_id ORDER BY date_of_birth DESC',array('campid'=>$_SESSION['camp']['id'],'parent_id'=>$f['id']));
		while($s = db_fetch($result2)) {
			echo '"";"'.$s['firstname'].'";"'.$s['lastname'].'";"'.$s['gender'].'";"'.strftime('%d-%m-%Y',strtotime($s['date_of_birth'])).'"'."\n";
		}
	}
	die();