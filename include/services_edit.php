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

$cmsmain->assign('title', 'Services');
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

$cmsmain->assign('include', 'cms_form.tpl');
$cmsmain->assign('data', $data);
$cmsmain->assign('formelements', $formdata);
$cmsmain->assign('formbuttons', $formbuttons);
