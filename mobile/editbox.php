<?php

    // displaying tags on the top of edit box
    // related trello https://trello.com/c/XjNwO3sL
    $box = db_row('
        SELECT  s.*, 
                CONCAT(p.name," ",g.label) AS product, 
                l.label AS location,
                GROUP_CONCAT(tags.label ORDER BY tags.seq SEPARATOR 0x1D) AS taglabels,
                GROUP_CONCAT(tags.color ORDER BY tags.seq) AS tagcolors,
                DATE_FORMAT(s.modified,"%Y/%m/%d") AS statemodified,
                bs.label AS statelabel,                        
                bs.id as stateid,
                l.type as locationType
        FROM stock AS s 
        LEFT OUTER JOIN box_state AS bs ON bs.id = s.box_state_id
        LEFT OUTER JOIN products AS p ON p.id = s.product_id 
        LEFT OUTER JOIN genders AS g ON g.id = p.gender_id 
        LEFT OUTER JOIN locations AS l ON l.id = s.location_id 
        LEFT OUTER JOIN tags_relations ON tags_relations.object_id = s.id AND tags_relations.object_type = "Stock"
        LEFT OUTER JOIN tags ON tags.id = tags_relations.tag_id AND tags_relations.object_type = "Stock" AND tags.deleted IS NULL
        WHERE s.id = :id', ['id' => $_GET['editbox']]);

    mobile_distro_check($box['locationType']);

    if (!is_null($box['deleted']) && '0000-00-00 00:00:00' != $box['deleted']) {
        // box is a deleted box
        unset($box['location_id']);
        $data['message'] = 'This box has been deleted. Editing and saving this form undeletes it.';
        $data['warning'] = true;
        trigger_error($data['message']);
    }

    if ($box['taglabels']) {
        $taglabels = explode(chr(0x1D), $box['taglabels']);
        $tagcolors = explode(',', $box['tagcolors']);
        foreach ($taglabels as $tagkey => $taglabel) {
            $data['headertags'][$tagkey] = [
                'label' => $taglabel,
                'color' => $tagcolors[$tagkey],
                'textcolor' => get_text_color($tagcolors[$tagkey]),
            ];
        }
    }

    // Forward to new Boxtribute App
    $message = v2_forward($settings['v2_base_url'], '/boxes/'.$box['box_id']);
    $data['message'] = (!isset($data['message']) ? $message : $data['message']);

    $data['products'] = db_array('SELECT p.id AS value, CONCAT(p.name, " " ,IFNULL(g.label,"")) AS label, sizegroup_id FROM products AS p LEFT OUTER JOIN genders AS g ON p.gender_id = g.id WHERE (NOT p.deleted OR p.deleted IS NULL) AND p.camp_id = :camp_id ORDER BY name', ['camp_id' => $_SESSION['camp']['id']]);
    $data['locations'] = db_array('
        SELECT
            l.id AS value, 
            if(l.box_state_id <> 1, concat(l.label," -  Boxes are ",bs.label),l.label) as label
        FROM locations l
        INNER JOIN box_state bs ON bs.id = l.box_state_id
        WHERE
            l.deleted IS NULL AND 
            l.camp_id = :camp_id AND 
            l.type = "Warehouse"
        ORDER BY l.seq', ['camp_id' => $_SESSION['camp']['id']]);
    $data['sizes'] = db_array('SELECT s.* FROM sizes AS s LEFT OUTER JOIN products AS p ON p.id = :product_id WHERE s.sizegroup_id = p.sizegroup_id ORDER BY s.seq', ['product_id' => $box['product_id']]);

    $data['allsizes'] = db_array('SELECT s.* FROM sizes AS s, sizegroup AS sg WHERE s.sizegroup_id = sg.id ORDER BY s.seq');
    $data['tags'] = db_query('SELECT 
                                tags.id AS value, 
                                tags.label, 
                                IF(tags_relations.object_id IS NOT NULL, 1,0) AS selected 
                              FROM tags 
                                LEFT JOIN tags_relations 
                                    ON tags.id = tags_relations.tag_id 
                                        AND tags_relations.object_id = :id
                                        AND tags_relations.object_type = "Stock" 
                              WHERE tags.camp_id = :campId AND tags.deleted IS NULL AND tags.type IN ("All","Stock")', ['id' => $_GET['editbox'], 'campId' => $_SESSION['camp']['id']]);
    $box['disabled'] = false;
    if (in_array($box['statelabel'], ['Lost', 'Scrap'])) {
        $box['disabled'] = true;
        $box['scrap'] = (in_array($box['statelabel'], ['Scrap']));
        $box['lost'] = (in_array($box['statelabel'], ['Lost']));
    }

    $tpl->assign('box', $box);
    $tpl->assign('include', 'mobile_newbox.tpl');
