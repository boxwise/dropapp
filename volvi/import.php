<?
	require('../library/core.php');
	
/*
	db_query('DELETE FROM volvi');
	$f = file_get_contents('csv.csv');
	$f = explode("\r\n",$f);
	array_shift($f);
	
	foreach($f as $p) {
		$p = explode(';',$p);
		if($p[1]=='Focal Point') {
			db_query('INSERT INTO volvi (parent_id, label, age, gender) VALUES (0,"'.$p[1].'",'.$p[2].',"'.$p[3].'")');
			$id = db_insertid();
		} else {
			db_query('INSERT INTO volvi (parent_id, label, age, gender) VALUES ('.$id.',"'.$p[1].'",'.$p[2].',"'.$p[3].'")');
		}
	}
*/
	
	echo db_value('SELECT COUNT(id) FROM volvi WHERE parent_id = 0').' families<br />';
	
	$size = db_simplearray('SELECT id, (SELECT COUNT(id) FROM volvi AS v2 WHERE v2.parent_id = v1.id)+1 AS familysize FROM volvi AS v1 WHERE v1.parent_id = 0');
	
	echo array_sum($size) / count($size).' average family size<br />';
	
	echo db_value('SELECT COUNT(id) FROM volvi WHERE gender = "Female" AND age >= 16').' females >=16<br />';
	echo db_value('SELECT COUNT(id) FROM volvi WHERE gender = "Male" AND age >= 16').' males >=16<br />';