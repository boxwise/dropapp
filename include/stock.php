<?php

use OpenCensus\Trace\Tracer;

Tracer::inSpan(
    ['name' => ('stock.php')],
    function () use ($action, &$cmsmain) {
        global $table, $listconfig, $listdata;

        $table = $action;
        $ajax = checkajax();

        if (!$ajax) {
            initlist();
            listsetting('manualquery', true);

            //title
            $cmsmain->assign('title', 'Manage Boxes');

            //search box
            listsetting('search', ['box_id', 'l.label', 's.label', 'g.label', 'p.name', 'stock.comments']);

            //Location filter
            listfilter(['label' => 'By Location', 'query' => 'SELECT id, label FROM locations WHERE deleted IS NULL AND NOT locations.box_state_id IN (2,6) AND camp_id = '.$_SESSION['camp']['id'].' AND type = "Warehouse" ORDER BY seq', 'filter' => 'l.id']);

            // Status Filter
            $statusarray = [
                'in_stock' => 'In Stock',
                'all' => 'All Box States',
                'donated' => 'Donated',
                'lost' => 'Lost',
                'scrap' => 'Scrap',
                // 'marked_for_shipment' => 'Marked for Shipment',
                'dispose' => 'Untouched for 3 months',
            ];
            listfilter2(['label' => 'Boxes', 'options' => $statusarray, 'filter' => '"show"']);
            // Set filter to InStock by default
            if (!isset($listconfig['filtervalue2'])) {
                $listconfig['filtervalue2'] = 'in_stock';
            }

            function get_filter2_query($applied_filter)
            {
                switch ($applied_filter) {
                case 'in_stock':
                    return ' AND stock.box_state_id = 1 ';

                case 'all':
                    return ' ';

                case 'donated':
                    return ' AND stock.box_state_id = 5';

                case 'lost':
                    return ' AND stock.box_state_id = 2';

                case 'scrap':
                    return ' AND stock.box_state_id = 6';

                case 'marked_for_shipment':
                    return ' AND stock.box_state_id = 3';

                case 'dispose':
                    return ' AND DATEDIFF(now(),stock.modified) > 90 AND stock.box_state_id = 1';

                default:
                    return ' AND stock.box_state_id = 1';
            }
            }
            $applied_filter2_query = get_filter2_query($_SESSION['filter2']['stock']);

            // Gender Filter
            $genders = db_simplearray('SELECT id AS value, label FROM genders ORDER BY seq');
            listfilter3(['label' => 'Gender', 'options' => $genders, 'filter' => '"s.gender_id"']);

            //Category Filter
            $itemlist = db_simplearray('SELECT pc.id, pc.label from products AS p INNER JOIN product_categories AS pc ON pc.id = p.category_id WHERE (camp_id = '.$_SESSION['camp']['id'].')');
            listfilter4(['label' => 'Category', 'options' => $itemlist, 'filter' => 'p.category_id']);

            // Tag Filter
            $tags = db_simplearray('SELECT id, label FROM tags WHERE camp_id = :camp_id AND deleted IS NULL AND `type` IN ("All", "Stock") ORDER BY seq', ['camp_id' => $_SESSION['camp']['id']]);
            if (!empty($tags)) {
                $tagfilter = ['id' => 'tagfilter', 'placeholder' => 'Tag filter', 'options' => db_array('SELECT id, id AS value, label, color FROM tags WHERE camp_id = :camp_id AND deleted IS NULL AND `type` in ("All","Stock") ORDER BY seq', ['camp_id' => $_SESSION['camp']['id']])];
                listsetting('multiplefilter', $tagfilter);
            }

            // Note for boxage: same day creation gets logged as 0 days
            $query = '
            SELECT
                    stock_filtered.*,
                    GROUP_CONCAT(tags.label ORDER BY tags.seq  SEPARATOR 0x1D) AS taglabels,
                    GROUP_CONCAT(tags.color ORDER BY tags.seq) AS tagcolors
                FROM
                    (SELECT 
                            stock.*, 
                            SUBSTRING(stock.comments,1, 25) AS shortcomment, 
                            g.label AS gender, p.name AS product, 
                            s.label AS size, l.label AS location, 
                            IF(DATEDIFF(now(),stock.created) = 1, "1 day", CONCAT(DATEDIFF(now(),stock.created), " days")) AS boxage,
                            stock.box_state_id IN (3,4) AS disableifistrue
                        FROM 
                            stock '.
                            // Join tags here only if a tag filter is selected and only boxes with a certain tag should be returned
                            ($listconfig['multiplefilter_selected'] ? '
                                LEFT JOIN
                                    tags_relations AS stock_tags_filter ON stock_tags_filter.object_id = stock.id AND stock_tags_filter.object_type = "Stock"
                                LEFT JOIN
                                    tags AS tags_filter ON tags_filter.id = stock_tags_filter.tag_id AND tags_filter.deleted IS NULL AND tags_filter.camp_id = '.$_SESSION['camp']['id'] : '').' 
                        LEFT OUTER JOIN 
                            products AS p ON p.id = stock.product_id
                        LEFT OUTER JOIN 
                            locations AS l ON l.id = stock.location_id
                        LEFT OUTER JOIN 
                            genders AS g ON g.id = p.gender_id
                        LEFT OUTER JOIN 
                            sizes AS s ON s.id = stock.size_id
                        WHERE 
                            l.type = "Warehouse" AND
                            (NOT stock.deleted OR stock.deleted IS NULL) AND
                            l.camp_id = '.$_SESSION['camp']['id'].

                            ($listconfig['searchvalue'] ? ' AND (box_id LIKE "%'.$listconfig['searchvalue'].'%" OR l.label LIKE "%'.$listconfig['searchvalue'].'%" OR s.label LIKE "%'.$listconfig['searchvalue'].'%" OR g.label LIKE "%'.$listconfig['searchvalue'].'%" OR p.name LIKE "%'.$listconfig['searchvalue'].'%" OR stock.comments LIKE "%'.$listconfig['searchvalue'].'%")' : '').

                            $applied_filter2_query.

                            ($_SESSION['filter3']['stock'] ? ' AND (p.gender_id = '.intval($_SESSION['filter3']['stock']).')' : '').

                            ($_SESSION['filter']['stock'] ? ' AND (stock.location_id = '.$_SESSION['filter']['stock'].')' : '').
                            ($_SESSION['filter4']['stock'] ? ' AND (p.category_id = '.$_SESSION['filter4']['stock'].')' : '').
                            // filter for boxes tags
                            ($listconfig['multiplefilter_selected'] ? ' AND tags_filter.id IN ('.implode(',', $listconfig['multiplefilter_selected']).') ' : '').'
                        GROUP BY 
                            stock.id ) AS stock_filtered
                    LEFT JOIN 
                        tags_relations ON tags_relations.object_id = stock_filtered.id AND tags_relations.object_type = "Stock"
                    LEFT JOIN
                        tags ON tags.id = tags_relations.tag_id AND tags.deleted IS NULL AND tags.camp_id = '.$_SESSION['camp']['id'].'
                    GROUP BY
                        stock_filtered.id
            ';
            $data = getlistdata($query);

            $totalboxes = 0;
            $totalitems = 0;
            foreach ($data as $key => $value) {
                if (3 == $data[$key]['box_state_id']) {
                    // ordered
                    $data[$key]['order'] = '<span class="hide">1</span><i class="fa fa-truck tooltip-this" title="This box is marked for a shipment."></i>';
                } elseif (4 == $data[$key]['box_state_id']) {
                    // picked
                    $data[$key]['order'] = '<span class="hide">2</span><i class="fa fa-truck green tooltip-this" title="This box is being shipped."></i>';
                } elseif (in_array(intval($data[$key]['box_state_id']), [2, 6])) {
                    $modifiedtext = $data[$key]['modified'] ? 'on '.strftime('%d-%m-%Y', strtotime($data[$key]['modified'])) : '';
                    $icon = 2 === intval($data[$key]['box_state_id']) ? 'fa-ban' : 'fa-chain-broken';
                    $statelabel = 2 === intval($data[$key]['box_state_id']) ? 'lost' : 'scrapped';
                    $data[$key]['order'] = sprintf('<span class="hide">3</span><i class="fa %s tooltip-this" style="color: red" title="This box was %s %s"></i>', $icon, $statelabel, $modifiedtext);
                } else {
                    $data[$key]['order'] = '<span class="hide">0</span>';
                }
                ++$totalboxes;
                $totalitems += $value['items'];

                if ($data[$key]['taglabels']) {
                    $taglabels = explode(chr(0x1D), $data[$key]['taglabels']);
                    $tagcolors = explode(',', $data[$key]['tagcolors']);
                    foreach ($taglabels as $tagkey => $taglabel) {
                        $data[$key]['tags'][$tagkey] = ['label' => $taglabel, 'color' => $tagcolors[$tagkey], 'textcolor' => get_text_color($tagcolors[$tagkey])];
                    }
                }

                // TODO add link to new app
                // $data[$key]['href'] = $settings['v2_base_url'].'/bases/'.$_SESSION['camp']['id'].'/boxes/'.$data[$key]['box_id'];
            }

            addcolumn('text', 'Box ID', 'box_id');
            addcolumn('text', 'Product', 'product');
            addcolumn('text', 'Gender', 'gender');
            addcolumn('text', 'Size', 'size');
            if (!empty($tags)) {
                addcolumn('tag', 'Tags', 'tags');
            }
            addcolumn('text', 'Comments', 'shortcomment');
            addcolumn('text', 'Items', 'items');
            addcolumn('text', 'Location', 'location');
            addcolumn('text', 'Age', 'boxage');
            addcolumn('html', '&nbsp;', 'order');

            listsetting('allowsort', true);
            listsetting('allowcopy', false);
            listsetting('add', 'Add');

            // TODO enable forward to new app only for beta users
            // if (in_array('beta_user', $_SESSION['auth0_user'][$settings['jwt_claim_prefix'].'/roles'])) {
            //     listsetting('beta_box_view_edit', true);
            // }

            // related to https://trello.com/c/Ci74t1Wj
            $locations = db_simplearray('SELECT 
                                            l.id AS value, if(l.box_state_id <> 1, concat(l.label," -  Boxes are ",bs.label),l.label) as label
                                        FROM
                                            locations l
                                            INNER JOIN box_state bs ON bs.id = l.box_state_id
                                        WHERE
                                            l.deleted IS NULL AND l.camp_id =  '.$_SESSION['camp']['id'].' 
                                                AND l.type = "Warehouse"
                                        ORDER BY l.seq');
            addbutton('export', 'Export', ['icon' => 'fa-download', 'showalways' => false]);
            if (!empty($tags)) {
                addbutton('tag', 'Add Tag', ['icon' => 'fa-tag', 'options' => $tags]);
                addbutton('rtag', 'Remove Tag', ['icon' => 'fa-tags', 'options' => $tags]);
            }
            addbutton('movebox', 'Move', ['icon' => 'fa-truck', 'options' => $locations, 'disableif' => true]);
            addbutton('qr', 'Make label', ['icon' => 'fa-print']);

            $cmsmain->assign('firstline', ['Total', $totalboxes.' boxes', $totalitems.' items', '', '', '', '', '', '', '']);
            $cmsmain->assign('listfooter', ['Total', $totalboxes.' boxes', $totalitems.' items', '', '', '', '', '', '', '']);

            $cmsmain->assign('data', $data);
            $cmsmain->assign('listconfig', $listconfig);
            $cmsmain->assign('listdata', $listdata);
            $cmsmain->assign('include', 'cms_list.tpl');
        } else {
            switch ($_POST['do']) {
            case 'movebox':
                //@todo: replace signle update/insert to bulk update/insert

                $ids = explode(',', $_POST['ids']);

                [$count, $message] = move_boxes($ids, $_POST['option']);

                $success = $count;
                $redirect = '?action='.$_GET['action'];

                break;

            case 'qr':
                $id = $_POST['ids'];
                $redirect = '/pdf/qr.php?label='.$id;

                break;

            case 'move':
                $ids = json_decode($_POST['ids']);
                list($success, $message, $redirect) = listMove($table, $ids);

                break;

            case 'delete':
                $stock_ids = explode(',', $_POST['ids']);
                [$success, $message, $redirect] = db_transaction(function () use ($table, $stock_ids) {
                    list($success, $message, $redirect) = listDelete($table, $stock_ids);

                    $params = [];
                    $query = 'DELETE FROM tags_relations WHERE object_type = "Stock" AND (`object_id`) IN (';
                    foreach ($stock_ids as $index => $stock_id) {
                        $query .= sprintf(' (:stock_id_%s) ', $index);

                        if (sizeof($stock_ids) - 1 !== $index) {
                            $query .= ', ';
                        } else {
                            $query .= ') ';
                        }

                        $params = array_merge($params, ['stock_id_'.$index => $stock_id]);
                    }
                    if (sizeof($params) > 0) {
                        db_query($query, $params);
                    }

                    return [$success, $message, $redirect];
                });

                break;

            case 'copy':
                $ids = explode(',', $_POST['ids']);
                list($success, $message, $redirect) = listCopy($table, $ids, 'menutitle');

                break;

            case 'hide':
                $ids = explode(',', $_POST['ids']);
                list($success, $message, $redirect) = listShowHide($table, $ids, 0);

                break;

            case 'show':
                $ids = explode(',', $_POST['ids']);
                list($success, $message, $redirect) = listShowHide($table, $ids, 1);

                break;

            case 'export':
                $_SESSION['export_ids_stock'] = $_POST['ids'];
                list($success, $message, $redirect) = [true, '', '?action=stock_export'];

                break;

            case 'tag':
                $ids = explode(',', $_POST['ids']);
                if ('undefined' == $_POST['option']) {
                    $success = false;
                    $message = 'No tags exist. Please go to "Manage tags" to create tags.';
                    $redirect = false;
                } else {
                    // set tag id
                    $tag_id = $_POST['option'];
                    $stock_ids = $ids;
                    if (sizeof($stock_ids) > 0) {
                        // Query speed optimised for 500 records from 3.2 seconds to 0.039 seconds using bulk inserts
                        $query = 'INSERT IGNORE INTO tags_relations (tag_id, object_type, `object_id`) VALUES ';

                        $params = [];

                        for ($i = 0; $i < sizeof($stock_ids); ++$i) {
                            $query .= "(:tag_id, 'Stock', :stock_id{$i})";
                            $params = array_merge($params, ['stock_id'.$i => $stock_ids[$i]]);
                            if ($i !== sizeof($stock_ids) - 1) {
                                $query .= ',';
                            }
                        }

                        $params = array_merge($params, ['tag_id' => $tag_id]);
                        db_query($query, $params);

                        $success = true;
                        $message = 'Tags added';
                        $redirect = true;
                    } else {
                        $success = false;
                        $message = 'To apply the tag, the beneficiary must be checked';
                        $redirect = false;
                    }
                }

                break;

            case 'rtag':
                $ids = explode(',', $_POST['ids']);
                if ('undefined' == $_POST['option']) {
                    $success = false;
                    $message = 'No tags exist. Please go to "Manage tags" to create tags.';
                    $redirect = false;
                } else {
                    // set tag id
                    $tag_id = $_POST['option'];
                    $stock_ids = $ids;
                    if (sizeof($stock_ids) > 0) {
                        db_transaction(function () use ($tag_id, $stock_ids) {
                            $query = 'DELETE FROM tags_relations WHERE object_type = "Stock" AND (tag_id, `object_id`) IN (';

                            $params = [];

                            for ($i = 0; $i < sizeof($stock_ids); ++$i) {
                                $query .= "(:tag_id, :stock_id{$i})";
                                $params = array_merge($params, ['stock_id'.$i => $stock_ids[$i]]);
                                if ($i !== sizeof($stock_ids) - 1) {
                                    $query .= ',';
                                } else {
                                    $query .= ')';
                                }
                            }

                            $params = array_merge($params, ['tag_id' => $tag_id]);
                            db_query($query, $params);
                        });
                        $success = true;
                        $message = 'Tags removed';
                        $redirect = true;
                    } else {
                        $success = false;
                        $message = 'To remove the tag, the boxes must be checked';
                        $redirect = false;
                    }
                }

                break;
        }

            $return = ['success' => $success, 'message' => $message, 'redirect' => $redirect];

            echo json_encode($return);

            exit();
        }
    }
);
