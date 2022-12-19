<?php

    $table = 'locations';
    $ajax = checkajax();

    if (!$ajax) {
        if (!$_SESSION['camp']['id']) {
            throw new Exception('The list of locations is not available when there is no camp selected');
        }

        $cmsmain->assign('title', 'Warehouse Locations');

        initlist();
        listsetting('haspagemenu', true);
        addpagemenu('active', 'In Use', ['link' => '?action=locations', 'testid' => 'active']);
        addpagemenu('deactivated', 'Archived', ['link' => '?action=locations_deactivated', 'active' => true, 'testid' => 'deactivated']);
        listsetting('allowsort', false);
        listsetting('allowmove', false);
        listsetting('allowshowhide', false);
        listsetting('allowdelete', false);
        listsetting('add', 'Add a location');
        listsetting('edit', 'locations_edit');

        addbutton('unarchive', 'Activate', ['icon' => 'fa-history', 'confirm' => true, 'testid' => 'reactivate-cms-user']);

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
            WHERE locations.deleted AND locations.type = "Warehouse"
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
            case 'unarchive':
                $ids = explode(',', $_POST['ids']);
                list($success, $message, $redirect) = listUndelete($table, $ids, false);
                $redirect = true;

                break;
        }

        $return = ['success' => $success, 'message' => $message, 'redirect' => $redirect];

        echo json_encode($return);

        exit();
    }
