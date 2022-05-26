<?php

    // displaying tags on the top of edit box
    // related trello https://trello.com/c/XjNwO3sL
    $box = db_row('
        SELECT  s.*, 
                CONCAT(p.name," ",g.label) AS product, 
                l.label AS location,
                GROUP_CONCAT(tags.label) AS taglabels,
                GROUP_CONCAT(tags.color) AS tagcolors
        FROM stock AS s 
        LEFT OUTER JOIN products AS p ON p.id = s.product_id 
        LEFT OUTER JOIN genders AS g ON g.id = p.gender_id 
        LEFT OUTER JOIN locations AS l ON l.id = s.location_id 
        LEFT OUTER JOIN tags_relations ON tags_relations.object_id = s.id AND tags_relations.object_type = "Stock"
        LEFT OUTER JOIN tags ON tags.id = tags_relations.tag_id AND tags_relations.object_type = "Stock" AND tags.deleted IS NULL
        WHERE s.id = :id', ['id' => $_GET['editbox']]);

    if (!is_null($box['deleted']) && '0000-00-00 00:00:00' != $box['deleted']) {
        // box is a deleted box
        unset($box['location_id']);
        $data['message'] = 'This box has been deleted. Editing and saving this form undeletes it.';
        $data['warning'] = true;
        trigger_error($data['message']);
    }

    if ($box['taglabels']) {
        $taglabels = explode(',', $box['taglabels']);
        $tagcolors = explode(',', $box['tagcolors']);
        foreach ($taglabels as $tagkey => $taglabel) {
            $data['tags'][$tagkey] = ['label' => $taglabel, 'color' => $tagcolors[$tagkey], 'textcolor' => get_text_color($tagcolors[$tagkey])];
        }
    }

    $data['products'] = db_array('SELECT p.id AS value, CONCAT(p.name, " " ,IFNULL(g.label,"")) AS label, sizegroup_id FROM products AS p LEFT OUTER JOIN genders AS g ON p.gender_id = g.id WHERE (NOT p.deleted OR p.deleted IS NULL) AND p.camp_id = :camp_id ORDER BY name', ['camp_id' => $_SESSION['camp']['id']]);
    $data['locations'] = db_array('SELECT *, id AS value FROM locations WHERE deleted IS NULL AND camp_id = :camp_id ORDER BY seq', ['camp_id' => $_SESSION['camp']['id']]);
    $data['sizes'] = db_array('SELECT s.* FROM sizes AS s LEFT OUTER JOIN products AS p ON p.id = :product_id WHERE s.sizegroup_id = p.sizegroup_id ORDER BY s.seq
', ['product_id' => $box['product_id']]);

    $data['allsizes'] = db_array('SELECT s.* FROM sizes AS s, sizegroup AS sg WHERE s.sizegroup_id = sg.id ORDER BY s.seq');

    $tpl->assign('box', $box);
    $tpl->assign('include', 'mobile_newbox.tpl');
