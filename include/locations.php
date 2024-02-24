<?php

    $table = $action;
    $ajax = checkajax();

    if (!$ajax) {
        if (!$_SESSION['camp']['id']) {
            throw new Exception('The list of locations is not available when there is no camp selected');
        }

        $cmsmain->assign('title', 'Warehouse Locations');

        initlist();
        listsetting('haspagemenu', true);
        addpagemenu('active', 'In Use', ['link' => '?action=locations', 'active' => true, 'testid' => 'active']);
        addpagemenu('deactivated', 'Archived', ['link' => '?action=locations_deactivated', 'testid' => 'deactivated']);
        listsetting('allowsort', false);
        listsetting('allowshowhide', false);
        listsetting('allowdelete', false);
        listsetting('add', 'Add a location');

        addbutton('archive', 'Archive', ['icon' => 'fa-archive', 'confirm' => true, 'testid' => 'reactivate-cms-user']);

        $data = getlistdata(
            'SELECT tmp.*, IF(tmp.boxcount, true, false) AS disableifistrue
             FROM
                (SELECT 
                    locations.*, 
                    (SELECT COUNT(id) FROM stock WHERE box_state_id = 1 AND location_id = locations.id AND locations.type = "Warehouse" AND NOT deleted) AS boxcount,
                    bs.label AS boxstate,
                    IF(locations.is_market OR locations.container_stock, true, false) AS preventedit,
                    0 as level 
                FROM locations
                LEFT JOIN box_state bs ON bs.id = locations.box_state_id
                WHERE locations.deleted IS NULL AND locations.type = "Warehouse"
                AND locations.camp_id = '.$_SESSION['camp']['id'].') AS tmp
            ORDER BY tmp.seq'
        );

        addcolumn('text', 'Warehouse Locations', 'label');
        addcolumn('text', 'Default Status of Boxes', 'boxstate');
        addcolumn('text', 'Number of Instock boxes ', 'boxcount');

        $cmsmain->assign('data', $data);
        $cmsmain->assign('listconfig', $listconfig);
        $cmsmain->assign('listdata', $listdata);
        $cmsmain->assign('include', 'cms_list.tpl');
    } else {
        switch ($_POST['do']) {
            case 'move':
                $ids = json_decode($_POST['ids']);
                [$success, $message, $redirect] = listMove($table, $ids);

                break;

            case 'archive':
                $ids = explode(',', $_POST['ids']);
                $ids_array = $ids;
                foreach ($ids as $id) {
                    if (db_value('SELECT id FROM stock WHERE location_id = :location AND box_state_id IN (1,3) AND (NOT deleted or deleted IS NULL) LIMIT 1', ['location' => $id])) {
                        $ids_array = array_diff($ids_array, [$id]);

                        break;
                    }
                }
                if ($ids_array != $ids) {
                    $success = false;
                    $message = 'Please move all Instock Boxes out of this location first!';
                    $redirect = false;
                } else {
                    [$success, $message, $redirect] = listDelete($table, $ids, false);
                    $redirect = true;
                }

                break;
        }

        $return = ['success' => $success, 'message' => $message, 'redirect' => $redirect];

        echo json_encode($return);

        exit();
    }
