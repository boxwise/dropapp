<?
$locations = join(',', db_simplearray('SELECT id, id FROM locations WHERE visible AND camp_id = :camp_id', array('camp_id' => $_SESSION['camp']['id'])));
$result = db_query('SELECT p.*, sg.label AS sizegroup, g.label AS gender, CONCAT(p.value," ' . $_SESSION['camp']['currencyname'] . '") AS drops, COALESCE(SUM(s.items),0) AS items, IF(SUM(s.items),1,0) AS preventdelete, pc.label AS category FROM products AS p
		LEFT OUTER JOIN genders AS g ON g.id = p.gender_id
		LEFT OUTER JOIN sizegroup AS sg ON sg.id = p.sizegroup_id
		LEFT OUTER JOIN stock AS s ON s.product_id = p.id AND NOT s.deleted ' . ($locations ? ' AND s.location_id IN (' . $locations . ')' : '') . '
		LEFT OUTER JOIN product_categories AS pc ON p.category_id = pc.id
		WHERE (NOT p.deleted OR p.deleted IS NULL) AND camp_id = ' . $_SESSION['camp']['id'] . '
		GROUP BY p.id ORDER BY category_id, g.seq, name
	');
$keys = array("name", "gender", "sizegroup", "items", "drops");

csvexport($result, "Products", $keys);
