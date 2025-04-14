<?php

$action = 'services_edit';
$table = 'services';

if ($_POST) {
    if ($_POST['do'] == 'export') {
        $return = ['success' => true, 'redirect' => '?action=services_export&id='.$id];

        echo json_encode($return);

        exit;
    }

    db_transaction(function () use ($table) {
        $_POST['camp_id'] = $_SESSION['camp']['id'];

        $handler = new formHandler($table);

        $savekeys = ['label', 'description', 'camp_id'];
        $id = $handler->savePost($savekeys);
    });

    redirect('?action='.$_POST['_origin']);
}

$cmsmain->assign('title', 'Service');
// display total number of services based on the object type
$data = db_row('SELECT 
                        services.*
                    FROM
                        services
                    WHERE
                        services.deleted IS NULL AND services.id = :id AND services.camp_id = :camp_id
                    GROUP BY services.id ', ['camp_id'=>$_SESSION['camp']['id'], 'id' => $id]);

addfield('text', 'Name', 'label', ['required' => true]);

addfield('textarea', 'Description', 'description');
addfield('created', 'Created', 'created', ['aside' => true]);
addfield('line', '', '');

$cmsmain->assign('include', 'cms_form.tpl');
$cmsmain->assign('data', $data);
$cmsmain->assign('formelements', $formdata);
$cmsmain->assign('formbuttons', $formbuttons);

// add an overview table of people that attended
$data=[];
$data = getlistdata('
SELECT 
	p.id,
    sr.created as used_on,
    u.naam as registered_by,
    p. firstname,
    p.lastname,
    p.container
FROM 
	services_relations sr
LEFT JOIN
    services s ON sr.service_id = s.id
LEFT JOIN 
	people p ON sr.people_id = p.id AND (NOT p.deleted OR p.deleted IS NULL)
LEFT JOIN
    cms_users u ON sr.created_by = u.id
WHERE sr.service_id = :id AND s.camp_id = :camp_id
ORDER BY sr.created DESC', ['camp_id'=>$_SESSION['camp']['id'], 'id' => $id]);

if ($data) {
    initlist();
    listsetting('edit', 'people_edit');
    listsetting('allowcopy', false);
    listsetting('allowmove', false);
    listsetting('allowadd', false);
    listsetting('allowselect', false);
    listsetting('allowselectall', false);
    listsetting('allowsort', true);
    listsetting('allowshowhide', false);
    listsetting('allowdelete', false);
    listsetting('nolisttitle', true);

    addcolumn('datetime', 'Used On', 'used_on');
    addcolumn('text', 'Registered By', 'registered_by');
    addcolumn('text', $_SESSION['camp']['familyidentifier'], 'container');
    addcolumn('text', 'Surname', 'lastname');
    addcolumn('text', 'Firstname', 'firstname');

    addbutton('register', 'Register Person', ['icon' => 'fa-plus', 'link' => '?action=use_service&service_id='.$id, 'showalways' => true, 'testid' => 'registerPersonButton']);
    addbutton('export', 'Export All', ['icon' => 'fa-download', 'showalways' => true, 'testid' => 'exportUsedServiceButton']);

    $cmsmain->assign('include2', 'cms_list.tpl');
    $cmsmain->assign('listconfig', $listconfig);
    $cmsmain->assign('listdata', $listdata);
    $cmsmain->assign('listcontentdata', $data);
}