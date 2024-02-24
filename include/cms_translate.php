<?php

$table = 'translate';
$ajax = checkajax();

if (!$ajax) {
    initlist();

    $cmsmain->assign('title', $translate['cms_translates']);

    listsetting('search', ['en', 'code', 'description']);
    // listfilter(array('label'=>'Filter op categorie','query'=>'SELECT c.id AS value, c.name AS label FROM translate_categories AS c, translate AS t WHERE t.category_id = c.id GROUP BY c.id ORDER BY c.id','filter'=>'category_id'));

    $hasdescription = db_fieldexists($table, 'description');

    $data = getlistdata('SELECT * FROM '.$table.(!$_SESSION['user']['is_admin'] ? ' WHERE NOT hidden' : ''));

    $width = intval(100 / (count($settings['languages']) + ($hasdescription ? 1 : 0) + ($_SESSION['user']['is_admin'] ? 1 : 0))).'%';

    foreach ($settings['languages'] as $language) {
        addcolumn('text', $language['name'], $language['code'], ['width' => $width]);
    }
    if ($hasdescription) {
        addcolumn('text', $translate['cms_translate_description'], 'description', ['width' => $width]);
    }

    if ($_SESSION['user']['is_admin']) {
        addcolumn('text', $translate['cms_translate_code'], 'code', ['width' => $width]);
    }

    listsetting('add', $translate['cms_translate_new']);
    listsetting('allowdelete', $_SESSION['user']['is_admin']);
    listsetting('allowadd', $_SESSION['user']['is_admin']);
    listsetting('allowcopy', true);
    listsetting('maxheight', 'window');

    $options = db_simplearray('SELECT id, name FROM translate_categories');
    addbutton('changecategory', 'Verplaatsen', ['icon' => 'fa-arrow-circle-right', 'options' => $options]);

    $cmsmain->assign('data', $data);
    $cmsmain->assign('listconfig', $listconfig);
    $cmsmain->assign('listdata', $listdata);
    $cmsmain->assign('include', 'cms_list.tpl');
} else {
    switch ($_POST['do']) {
        case 'changecategory':
            $ids = explode(',', $_POST['ids']);
            foreach ($ids as $id) {
                db_query('UPDATE '.$table.' SET category_id = :option WHERE id = :id', ['option' => $_POST['option'], 'id' => $id]);
            }
            $success = true;
            $message = $translate['cms_translate_categorychanged'];
            $redirect = '?action='.$_GET['action'].'&filter='.$_POST['option'];

            break;

        case 'move':
            $ids = json_decode($_POST['ids']);
            [$success, $message, $redirect] = listMove($table, $ids);

            break;

        case 'delete':
            $ids = explode(',', $_POST['ids']);
            [$success, $message, $redirect] = listDelete($table, $ids);

            break;

        case 'copy':
            $ids = explode(',', $_POST['ids']);
            [$success, $message, $redirect] = listCopy($table, $ids, 'code');

            break;

        case 'hide':
            $ids = explode(',', $_POST['ids']);
            [$success, $message, $redirect] = listShowHide($table, $ids, 0);

            break;

        case 'show':
            $ids = explode(',', $_POST['ids']);
            [$success, $message, $redirect] = listShowHide($table, $ids, 1);

            break;

        default:
            $success = false;
            $message = $translate['cms_list_notexistingdo'];
            $redirect = false;

            break;
    }

    $return = ['success' => $success, 'message' => $message, 'redirect' => $redirect];

    echo json_encode($return);

    exit;
}
