<?
    $data = db_query('SELECT boxes.*, cu.naam AS ordered_name, cu2.naam AS picked_name, SUBSTRING(boxes.comments,1, 25) AS shortcomment, g.label AS gender, p.name AS product, s.label AS size, l.label AS location, IF(DATEDIFF(now(),boxes.modified) > 90,1,0) AS oldbox FROM stock as boxes
    LEFT OUTER JOIN cms_users AS cu ON cu.id = boxes.ordered_by
    LEFT OUTER JOIN cms_users AS cu2 ON cu2.id = boxes.picked_by
    LEFT OUTER JOIN products AS p ON p.id = boxes.product_id
    LEFT OUTER JOIN locations AS l ON l.id = boxes.location_id
    LEFT OUTER JOIN genders AS g ON g.id = p.gender_id
    LEFT OUTER JOIN sizes AS s ON s.id = boxes.size_id ');

	$keys = array('box_id'=>'Box number', 'product'=>'Product', 'gender'=>'Gender', 'size'=>'Size', 'location'=>'Location');


	$keys = array("box_id","product","gender","size","location");
	csvexport($data,"Stock",$keys);
