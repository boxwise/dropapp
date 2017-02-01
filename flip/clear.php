<?
	
	include('flip.php');
	
	$result = db_query('SELECT s.* FROM stock AS s LEFT OUTER JOIN products AS p ON p.id = s.product_id LEFT OUTER JOIN locations AS l ON l.id = s.location_id WHERE NOT s.deleted AND l.visible AND (qr_id = 0 OR qr_id IS NULL)');
	$i=0;
	while($row = db_fetch($result)) {
		$i++;
// 		dump($row);
		db_query('UPDATE stock SET deleted = 1 WHERE id = :id ',array('id'=>$row['id']));
		echo $row['box_id']. ' - ' . $row['name'] . ' - ' . $row['items'] . ' - ' . $row['location_id'] .'<br />';
		
	}
	echo $i.' boxes to be removed<br />';
