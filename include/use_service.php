<?php

$action = 'use_service';
$table = 'services_relations';

if ($_POST) {
    db_transaction(function () use ($table) {
        $peopleids = $_POST['people_id'];

        $savekeys = ['service_id', 'people_id'];
        foreach ($peopleids as $key => $value) {
            $_POST['people_id'] = $value;
            $handler = new formHandler($table);
            $handler->savePost($savekeys);
        }
    });

    redirect('?action=use_service&service_id='.$_POST['service_id'][0]);
}

$cmsmain->assign('title', 'Register Service Usage');

$serviceoptions = db_array('
    SELECT 
        id AS value, label
    FROM 
        services
    WHERE 
        deleted IS NULL AND camp_id = :camp_id', ['camp_id' => $_SESSION['camp']['id']]);
addfield('select', 'Service', 'service_id', ['placeholder' => 'Type to search', 'required' => true, 'options' => $serviceoptions]);
$beneficiaryoptions = db_array('
    SELECT 
        id AS value, CONCAT(container, " ",firstname, " ", lastname) AS label
    FROM 
        people
    WHERE 
        deleted IS NULL AND camp_id = :camp_id', ['camp_id' => $_SESSION['camp']['id']]);
addfield('select', 'Beneficiaries', 'people_id', ['placeholder' => 'Type to search', 'required' => true, 'multiple' => true, 'options' => $beneficiaryoptions]);

$service_id = $_GET['service_id'];
if ($service_id) {
    $data = db_row('
        SELECT 
            id as service_id,
            label
        FROM
            services
        WHERE
            deleted IS NULL AND services.id = :id AND services.camp_id = :camp_id', ['camp_id' => $_SESSION['camp']['id'], 'id' => $service_id]);
    $cmsmain->assign('data', $data);
}
$translate['cms_form_submit'] = 'Save';
$cmsmain->assign('translate', $translate);
$cmsmain->assign('include', 'cms_form.tpl');
$cmsmain->assign('formelements', $formdata);
$cmsmain->assign('formbuttons', $formbuttons);
