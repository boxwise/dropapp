<?php

$table = $action;
$ajax = checkajax();

if (!$ajax) {
    initlist();

    $cmsmain->assign('title', 'Organisations');
    listsetting('search', ['o.label']);

    $data = getlistdata('SELECT * FROM '.$table);

    addcolumn('text', 'Name', 'label');

    listsetting('allowsort', true);
    listsetting('allowdelete', false);
    $listconfig['allowselectall'] = false;
    $listconfig['allowselect'] = false;
    listsetting('add', 'Add an organisation');

    $cmsmain->assign('data', $data);
    $cmsmain->assign('listconfig', $listconfig);
    $cmsmain->assign('listdata', $listdata);
    $cmsmain->assign('include', 'cms_list.tpl');
}
