<?php

    $table = $action;
    $ajax = checkajax();

    if (!$ajax) {
        initlist();

        $cmsmain->assign('title', 'Bases');

        $data = getlistdata('SELECT *, IF('.intval($_SESSION['camp']['id']).'=id,1,0) AS preventdelete FROM camps WHERE organisation_id = '.intval($_SESSION['organisation']['id']));

        addcolumn('text', 'Name', 'name');

        listsetting('add', 'Add a base');

        $listconfig['allowmove'] = false;
        $listconfig['allowdelete'] = false;
        $listconfig['allowselectall'] = false;
        $listconfig['allowselect'] = false;

        $cmsmain->assign('data', $data);
        $cmsmain->assign('listconfig', $listconfig);
        $cmsmain->assign('listdata', $listdata);
        $cmsmain->assign('include', 'cms_list.tpl');
    }
