<?

	header("Content-type: text/csv");
	header("Content-Disposition: attachment; filename=allproducts.csv");
	header("Pragma: no-cache");
	header("Expires: 0");

	$ajax = checkajax();

	$locations = join(',',db_simplearray('SELECT id, id FROM locations WHERE visible AND camp_id = :camp_id',array('camp_id'=>$_SESSION['camp']['id'])));

	$result = db_query('SELECT products.*, sg.label AS sizegroup, g.label AS gender, CONCAT(products.value," '.$_SESSION['camp']['currencyname'].'") AS drops, COALESCE(SUM(s.items),0) AS items, IF(SUM(s.items),1,0) AS preventdelete, pc.label AS category FROM products
		LEFT OUTER JOIN genders AS g ON g.id = products.gender_id
		LEFT OUTER JOIN sizegroup AS sg ON sg.id = products.sizegroup_id
		LEFT OUTER JOIN stock AS s ON s.product_id = products.id AND NOT s.deleted AND s.location_id IN ('.$locations.') 
		LEFT OUTER JOIN product_categories AS pc ON products.category_id = pc.id
		WHERE NOT products.deleted AND camp_id = '.$_SESSION['camp']['id'].'
		GROUP BY products.id ORDER BY category_id, g.seq, name
	');

	echo '"Product";"Gender";"Size group";"Items";"'.ucfirst($_SESSION['camp']['currencyname']).'"'."\n";
	
	while($f = db_fetch($result)) {
		if($f['category']!=$old['category']) echo "\n".'"'.$f['category'].'"'."\n";
		
		echo '"'.$f['name'].'";"'.$f['gender'].'";"'.$f['sizegroup'].'";"'.$f['items'].'";"'.$f['drops'].'"'."\n";
		
		$old = $f;

	}
	die();