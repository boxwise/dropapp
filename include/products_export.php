<?php

$locations = join(',', db_simplearray('SELECT id, id FROM locations WHERE visible AND camp_id = :camp_id', ['camp_id' => $_SESSION['camp']['id']]));
$result = db_query(
    'SELECT p.*, sg.label AS sizegroup, g.label AS gender, p.value AS drops, COALESCE(SUM(s.items),0) AS items, pc.label AS category 
		FROM products AS p
		LEFT OUTER JOIN genders AS g ON g.id = p.gender_id
		LEFT OUTER JOIN sizegroup AS sg ON sg.id = p.sizegroup_id
		LEFT OUTER JOIN stock AS s ON s.product_id = p.id AND NOT s.deleted '.($locations ? ' AND s.location_id IN ('.$locations.')' : '').'
		LEFT OUTER JOIN product_categories AS pc ON p.category_id = pc.id
		WHERE (NOT p.deleted OR p.deleted IS NULL) AND camp_id = :camp_id '.($_GET['ids'] ? 'AND p.id IN ('.$_GET['ids'].')' : 'AND FALSE').'
		GROUP BY p.id ORDER BY category_id, g.seq, name',
    ['camp_id' => $_SESSION['camp']['id']]
);
$keys = ['name' => 'Product', 'category' => 'Category', 'gender' => 'Target Group', 'sizegroup' => 'Sizegroup', 'items' => 'Items', 'drops' => $_SESSION['camp']['currencyname']];

csvexport($result, 'Products', $keys);
