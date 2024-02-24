<?php

$table = 'borrow_items';
$action = 'borrow';

if ($_POST) {
    if ($_POST['pass']) {
        $_POST['pass'] = md5($_POST['pass']);
    }

    $handler = new formHandler($table);

    $savekeys = ['label', 'category_id', 'visible', 'comment', 'location_id'];
    $id = $handler->savePost($savekeys);

    redirect('?action=borrow');
}

$data = db_row('SELECT * FROM '.$table.' WHERE id = :id', ['id' => $id]);

if (!$id) {
    $data['visible'] = 1;
    $cmsmain->assign('title', 'Add a new item to borrow');
} else {
    $cmsmain->assign('title', $data['label']);
}

$cmsmain->assign('include', 'cms_form.tpl');

addfield('text', 'Label', 'label');
addfield('select', 'Category', 'category_id', ['required' => true, 'query' => 'SELECT id AS value, label FROM borrow_categories']);
addfield('select', 'Location', 'location_id', ['required' => true, 'query' => 'SELECT id AS value, location AS label FROM borrow_locations WHERE camp_id = '.intval($_SESSION['camp']['id'])]);
addfield('checkbox', 'Available', 'visible');
addfield('textarea', 'Comments', 'comment');

addfield('line', '', '');

addfield('created', 'Created', 'created', ['aside' => true]);

$cmsmain->assign('data', $data);
$cmsmain->assign('formelements', $formdata);
$cmsmain->assign('formbuttons', $formbuttons);
