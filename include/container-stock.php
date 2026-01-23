<?php

$table = 'stock';

initlist();

$cmsmain->assign('title', 'Stockroom');
listsetting('search', ['p.name']);

$container_stock_locations = join(',', db_simplearray('SELECT id, id FROM locations WHERE box_state_id = 1 AND type = "Warehouse" AND container_stock AND deleted IS NULL AND camp_id = :camp_id', ['camp_id' => $_SESSION['camp']['id']]));

if ($container_stock_locations) {
    $data = getlistdata('
	SELECT
		CONCAT(p.id,"-",g.id,"-",s.id) AS id,
		p.name,
		g.label AS gender,
		s.label AS size,
		IFNULL(COUNT(s2.id),0) AS boxes,
		IFNULL(SUM(s2.items),0) AS stock, 
		(SELECT COUNT(s3.id) FROM stock AS s3
			LEFT OUTER JOIN locations AS l2 ON l2.id = s3.location_id
			WHERE l2.type = "Warehouse" AND 
                (NOT s3.deleted OR s3.deleted IS NULL) AND 
                s3.product_id = p.id AND 
                p.gender_id = g.id AND 
                s3.size_id = s.id AND 
                s3.box_state_id = 1 AND 
                l2.camp_id='.$_SESSION['camp']['id'].')-IFNULL(COUNT(s2.id),0) AS totalboxes
	FROM
		(products AS p,
		sizes AS s)
	LEFT OUTER JOIN genders AS g ON p.gender_id = g.id
	LEFT OUTER JOIN 
        stock AS s2 
        ON s2.product_id = p.id AND 
        s2.size_id = s.id AND 
        (NOT s2.deleted OR s2.deleted IS NULL) AND 
        s2.box_state_id = 1 AND
        s2.location_id IN ('.$container_stock_locations.')
	WHERE
		(NOT p.deleted OR p.deleted IS NULL) AND
		s.sizegroup_id = p.sizegroup_id AND
		p.camp_id = '.$_SESSION['camp']['id'].'
		'.($_SESSION['search']['container-stock'] ? 'AND p.name LIKE "%'.$_SESSION['search']['container-stock'].'%"' : '').'
	GROUP BY
		p.id, p.name, g.id, g.label, s.id, s.label, p.stockincontainer
	HAVING COUNT(s2.id) > 0 OR p.stockincontainer
	ORDER BY stock DESC, boxes DESC, name, gender, size');

    foreach ($data as $key => $d) {
        $totalboxes += $d['boxes'];
        $totalitems += $d['stock'];
    }

    listsetting('allowcopy', false);
    listsetting('allowadd', false);
    listsetting('allowdelete', false);
    listsetting('allowselect', false);
    listsetting('allowselectall', false);
    listsetting('allowsort', true);
    listsetting('maxheight', false);

    addcolumn('text', 'Product', 'name');
    addcolumn('text', 'Gender', 'gender');
    addcolumn('text', 'Size', 'size');
    addcolumn('text', 'Boxes', 'boxes');
    addcolumn('text', 'Items', 'stock');
    addcolumn('text', 'Boxes elsewhere', 'totalboxes');

    $cmsmain->assign('firstline', ['Total Instock boxes/items', '', '', $totalboxes, $totalitems, '']);
    $cmsmain->assign('listfooter', ['Total Instock boxes/items', '', '', $totalboxes, $totalitems, '']);

    $cmsmain->assign('data', $data);
    $cmsmain->assign('listconfig', $listconfig);
    $cmsmain->assign('listdata', $listdata);
    $cmsmain->assign('include', 'cms_list.tpl');
} else {
    $cmsmain->assign('list_info_text', "You have not set up a stockroom for your free shop. Stockroom is a way to help free shops keep track of items that aren't on display or that always need to be in stock. If you need this functionality, ask your coordinator to enable it.");
    $cmsmain->assign('include', 'cms_list.tpl');
}
