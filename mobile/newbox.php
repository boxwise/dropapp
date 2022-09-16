<?php

    $box['id'] = 'new';
    $box['qr_id'] = db_value('SELECT id FROM qr WHERE code = :barcode AND legacy = :legacy', ['barcode' => $_GET['newbox'], 'legacy' => (isset($_GET['qrlegacy']) ? 1 : 0)]);
    if (!isset($box['qr_id'])) {
        throw new Exception('No QR-code assigned for new box!');
    }
    $data['products'] = db_array('SELECT p.id AS value, CONCAT(p.name, " " ,IFNULL(g.label,"")) AS label, sizegroup_id FROM products AS p LEFT OUTER JOIN genders AS g ON p.gender_id = g.id WHERE (NOT p.deleted OR p.deleted IS NULL) AND camp_id = :camp_id'.($_SESSION['camp']['separateshopandwhproducts'] ? ' AND NOT p.stockincontainer' : '').' ORDER BY name', ['camp_id' => $_SESSION['camp']['id']]);
    $data['locations'] = db_array('SELECT 

    l.id AS value, if(l.box_state_id <> 1, concat(l.label," -  Boxes are ",bs.label),l.label) as label
FROM
    locations l
    INNER JOIN box_state bs ON bs.id = l.box_state_id AND NOT l.box_state_id <> 1
WHERE
    l.deleted IS NULL AND l.camp_id = :camp_id 
        AND l.type = "Warehouse"
ORDER BY l.seq', ['camp_id' => $_SESSION['camp']['id']]);

    $data['allsizes'] = db_array('SELECT s.* FROM sizes AS s, sizegroup AS sg WHERE s.sizegroup_id = sg.id ORDER BY s.seq');
    // adding the tags to box creation form
    // related trello https://trello.com/c/XjNwO3sL
    $data['tags'] = db_query('SELECT 
                            tags.id AS value, 
                            tags.label 
                            FROM tags 
                            LEFT JOIN tags_relations 
                                ON tags.id = tags_relations.tag_id 
                                    AND tags_relations.object_type = "Stock" 
                            WHERE tags.camp_id = '.$_SESSION['camp']['id'].' AND tags.deleted IS NULL AND tags.type IN ("All","Stock") 
                            GROUP BY tags.id
                            ORDER BY seq');
    $tpl->assign('box', $box);
    $tpl->assign('include', 'mobile_newbox.tpl');
