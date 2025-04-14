<?php

$action = 'services_edit';
$table = 'services';

if ($_POST) {
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
                        services.deleted IS NULL AND services.id = :id
                    GROUP BY services.id ', ['id' => $id]);

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
    sr.created,
    sr.created_by,
    p. firstname,
    p.lastname,
    p.container
FROM 
	services_relations sr
LEFT JOIN 
	people p ON sr.people_id = p.id AND (NOT p.deleted OR p.deleted IS NULL)
WHERE sr.service_id = :id
ORDER BY sr.created DESC', ['id' => $id]);

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

    addcolumn('text', $_SESSION['camp']['familyidentifier'], 'container');
    addcolumn('text', 'Surname', 'lastname');
    addcolumn('text', 'Firstname', 'firstname');
    addcolumn('datetime', 'Used On', 'created');

    $cmsmain->assign('include2', 'cms_list.tpl');
    $cmsmain->assign('listconfig', $listconfig);
    $cmsmain->assign('listdata', $listdata);
    $cmsmain->assign('listcontentdata', $data);
}