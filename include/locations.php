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
        listsetting('add', 'Add a location');
        listsetting('delete', 'Archive');

        listsetting('search', ['locations.label']);

        $data = getlistdata(
            'SELECT 
                locations.*, 
                (SELECT COUNT(id) FROM stock WHERE location_id = locations.id AND locations.type = "Warehouse" AND NOT deleted) AS boxcount,
                bs.label AS boxstate,
                IF(locations.is_market OR locations.container_stock, true, false) AS preventedit,
                0 as level 
            FROM locations
            LEFT JOIN box_state bs ON bs.id = locations.box_state_id
            WHERE locations.deleted IS NULL AND locations.type = "Warehouse"
            AND locations.camp_id = '.$_SESSION['camp']['id']
        );

        addcolumn('text', 'Warehouse Locations', 'label');
        addcolumn('text', 'Default box state', 'boxstate');
        addcolumn('text', 'Number of boxes', 'boxcount');

        $cmsmain->assign('data', $data);
        $cmsmain->assign('listconfig', $listconfig);
        $cmsmain->assign('listdata', $listdata);
        $cmsmain->assign('include', 'cms_list.tpl');
    } else {
        switch ($_POST['do']) {
            case 'move':
                $ids = json_decode($_POST['ids']);
                list($success, $message, $redirect) = listMove($table, $ids);

                break;

            case 'delete':
                $ids = explode(',', $_POST['ids']);
                list($success, $message, $redirect) = listDelete($table, $ids, false);

                break;
        }

        $return = ['success' => $success, 'message' => $message, 'redirect' => $redirect];

        echo json_encode($return);

        exit();
    }
