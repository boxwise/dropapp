<?php

$table = 'camps';
$action = 'camps_edit';

if ($_POST) {
    db_transaction(function () use ($table, $rolesToActions, $menusToActions) {
        $handler = new formHandler($table);
        $baseName = trim((string) $_POST['name']);
        $baseIsNew = !(!empty($_POST['id']) && preg_match('/\d+/', (string) $_POST['id']));

        $savekeys = [
            'name',
            'market',
            'familyidentifier',
            'delete_inactive_users',
            'food',
            'bicycle',
            'idcard',
            'workshop',
            'laundry',
            'schedulestart',
            'schedulestop',
            'schedulebreak',
            'schedulebreakstart',
            'schedulebreakduration',
            'scheduletimeslot',
            'currencyname',
            'dropsperadult',
            'dropsperchild',
            'dropcapadult',
            'dropcapchild',
            'bicyclerenttime',
            'adult_age',
            'daystokeepdeletedpersons',
            'extraportion',
            'maxfooddrops_adult',
            'maxfooddrops_child',
            'bicycle_closingtime',
            'bicycle_closingtime_saturday',
            'organisation_id',
            'resettokens',
            'beneficiaryisregistered',
            'beneficiaryisvolunteer',
            'email_enabled',
            'phone_enabled',
            'additional_field1_enabled',
            'additional_field2_enabled',
            'additional_field3_enabled',
            'additional_field4_enabled',
            'additional_field1_label',
            'additional_field2_label',
            'additional_field3_label',
            'additional_field4_label',
        ];
        $id = $handler->savePost($savekeys);
        // $handler->saveMultiple('functions', 'cms_functions_camps', 'camps_id', 'cms_functions_id');
        if ($baseIsNew) {
            createRolesForBase($_SESSION['organisation']['id'], $_SESSION['organisation']['label'], $id, $baseName, $rolesToActions, $menusToActions, false);
        } else {
            updateRolesForBase($id, $baseName);
        }
        $_SESSION['camp'] = getcampdata($_SESSION['camp']['id']);
    });

    redirect('?action='.$_POST['_origin']);
}

$data = db_row('SELECT * FROM '.$table.' WHERE id = :id', ['id' => $id]);

if (!$id) {
    $data = db_defaults($table);
    $data['visible'] = 1;
    $data['organisation_id'] = $_SESSION['organisation']['id'];
}

// open the template
$cmsmain->assign('include', 'cms_form.tpl');
addfield('hidden', '', 'id');
addfield('hidden', '', 'organisation_id');

// put a title above the form
$cmsmain->assign('title', 'Base');

$tabs['general'] = 'General Settings';
$tabs['beneficiaries'] = 'Beneficiaries';
$tabs['market'] = 'Free Shop';
$tabs['food'] = 'Food Distribution';
$tabs['bicycle'] = 'Rent Bicycles';
$cmsmain->assign('tabs', $tabs);

// Specify when tabs should be hidden
$hiddentabs['market'] = !$data['market'];
$hiddentabs['food'] = !$data['food'];
$hiddentabs['bicycle'] = !$data['bicycle'];
$cmsmain->assign('hiddentabs', $hiddentabs);

addfield('text', 'Base name', 'name', ['setformtitle' => true, 'tab' => 'general', 'required' => true]);
addfield('line', '', '', ['tab' => 'general']);

addfield('title', 'Features', '', ['tab' => 'general']);
addfield('select', 'Functions available for this base', 'functions', ['width' => 6, 'placeholder' => 'This will be automatically filled.', 'disabled' => true, 'tab' => 'general', 'multiple' => true, 'query' => '
    	SELECT a.id AS value, a.title_en AS label, IF(x.camps_id IS NOT NULL, 1,0) AS selected
    	FROM cms_functions AS a
    	LEFT OUTER JOIN cms_functions_camps AS x ON a.id = x.cms_functions_id AND x.camps_id = '.intval($id).'
    	WHERE a.parent_id IS NOT NULL AND a.visible AND NOT a.allcamps AND NOT a.adminonly AND NOT a.allusers
    	ORDER BY seq']);

addfield('checkbox', 'You have a Free Shop?', 'market', ['tab' => 'general', 'onchange' => 'toggleShop()']);
// addfield('checkbox', 'You run a food distribution program in the Free Shop?', 'food', ['tab' => 'general', 'onchange' => 'toggleFood()']);
// addfield('checkbox', 'You run a Bicycle/tools borrowing program?', 'bicycle', array('tab'=>'general', 'onchange'=>'toggleBikes()'));
// addfield('checkbox', 'You have a workshop for beneficiaries?', 'workshop', array('tab'=>'general'));
// addfield('checkbox', 'You run a laundry station for beneficiaries?', 'laundry', array('tab'=>'general'));
addfield('line', '', '', ['tab' => 'general']);

addfield('number', 'Deactivate inactive beneficiaries', 'delete_inactive_users', ['tab' => 'beneficiaries', 'width' => 2, 'tooltip' => 'Beneficiaries without activity in Boxtribute will be deactivated. Deactivated beneficiaries will remain visible in the Deactivated tab in the Beneficiaries page.']);
addfield('number', 'Days to keep deactivated persons', 'daystokeepdeletedpersons', ['tab' => 'beneficiaries', 'width' => 2, 'tooltip' => 'Deactivate beneficiaries will remain visible in the Deactivated tab in the beneficiaries page and will be completely deleted after a while. Here you can define how long they will remain in the Deactivated list.']);
addfield('number', 'Adult age', 'adult_age', ['tab' => 'beneficiaries', 'width' => 2, 'tooltip' => 'For some functions we distinct between children and adults. Fill in here the lowest age considered adult for this base.']);
addfield('text', 'Location identifier for beneficiaries', 'familyidentifier', ['tab' => 'beneficiaries', 'tooltip' => 'beneficiariesly this refers to the kind of housing that people have: tent, container, house or something else.']);
addfield('checkbox', 'Do you give out beneficiaries ID-cards?', 'idcard', ['tab' => 'beneficiaries']);
addfield('checkbox', 'Do you track if your beneficiaries are officially registered?', 'beneficiaryisregistered', ['tab' => 'beneficiaries']);
addfield('checkbox', 'Can your beneficiaries be volunteers in your organisation?', 'beneficiaryisvolunteer', ['tab' => 'beneficiaries']);

// Add options to choose to enable email and/or phone fields, and to define custom fields for the beneficiary form
// Trello Ref. https://trello.com/c/WmBmSHyp
addfield('checkbox', 'Enable email field for beneficiaries?', 'email_enabled', [
    'tab' => 'beneficiaries',
    'tooltip' => 'Allow users to enter an email address for the beneficiary. This field is optional and can be used for communication purposes.',
]);

addfield('checkbox', 'Enable phone number field for beneficiaries?', 'phone_enabled', [
    'tab' => 'beneficiaries',
    'tooltip' => 'Allow users to enter a phone number for the beneficiary. This field is optional and can be used for direct communication.',
]);

addfield('line', '', '', ['tab' => 'beneficiaries']);

addfield('title', 'Enable Additional Custom Fields', '', ['tab' => 'beneficiaries', 'tooltip' => 'If the checkbox is enabled, a text field will be added to the beneficiary form with the provided label.']);

// Custom Text Field 1 (Free Text)
addfield('checkbox', 'Enable text field 1 (e.g., Family Size)', 'additional_field1_enabled', [
    'tab' => 'beneficiaries',
    'size' => 2,
    'onchange' => 'conditionalToggle("additional_field1_enabled", "additional_field1_label")',
]);

addfield('text', 'Text field 1 label', 'additional_field1_label', [
    'tab' => 'beneficiaries',
    'disabled' => $data['additional_field1_enabled'] ? false : true,
    'required' => $data['additional_field1_enabled'],
    'tooltip' => 'Provide a label for this field (e.g., "Family Size" or "Occupation").',
]);

// Custom Text Field 2 (Free Text)
addfield('checkbox', 'Enable text field 2 (e.g., Living Area)', 'additional_field2_enabled', [
    'tab' => 'beneficiaries',
    'onchange' => 'conditionalToggle("additional_field2_enabled", "additional_field2_label")',
    'checked' => $data['additional_field2_enabled'],
]);

addfield('text', 'Text field 2 label', 'additional_field2_label', [
    'tab' => 'beneficiaries',
    'disabled' => $data['additional_field2_enabled'] ? false : true,
    'required' => $data['additional_field2_enabled'],
    'tooltip' => 'Provide a label for the second custom field (e.g., "Living Area" or "Notes").',
]);

addfield('line', '', '', ['tab' => 'beneficiaries']);

// Custom Number Field (e.g., Number of Family Members)
addfield('checkbox', 'Enable number field (e.g., Family Members)', 'additional_field3_enabled', [
    'tab' => 'beneficiaries',
    'onchange' => 'conditionalToggle("additional_field3_enabled", "additional_field3_label")',
]);

addfield('text', 'Number field label', 'additional_field3_label', [
    'tab' => 'beneficiaries',
    'disabled' => $data['additional_field3_enabled'] ? false : true,
    'required' => $data['additional_field3_enabled'],
    'tooltip' => 'Provide a label for the number field (e.g., "Number of Family Members").',
]);

addfield('line', '', '', ['tab' => 'beneficiaries']);

// Custom Date Field (e.g., Next Visit Date)
addfield('checkbox', 'Enable date field (e.g., Next Visit Date)', 'additional_field4_enabled', [
    'tab' => 'beneficiaries',
    'onchange' => 'conditionalToggle("additional_field4_enabled", "additional_field4_label")',
]);

addfield('text', 'Date field label', 'additional_field4_label', [
    'tab' => 'beneficiaries',
    'disabled' => $data['additional_field4_enabled'] ? false : true,
    'required' => $data['additional_field4_enabled'],
    'tooltip' => 'Provide a label for the date field (e.g., "Next Visit Date").',
]);

addfield('text', 'Currency name', 'currencyname', ['tab' => 'market', 'required' => true, 'width' => 2, 'tooltip' => 'Will get active after first page reload']);
addfield('line', '', '', ['tab' => 'market']);
addfield('title', $_SESSION['camp']['currencyname'].' per cycle', '', ['tab' => 'market']);
addfield('number', $_SESSION['camp']['currencyname'].' per adult', 'dropsperadult', ['tab' => 'market', 'width' => 2]);
addfield('number', $_SESSION['camp']['currencyname'].' per child', 'dropsperchild', ['tab' => 'market', 'width' => 2]);
addfield('checkbox', 'Reset tokens on cycle restart', 'resettokens', ['tab' => 'market']);
addfield('line', '', '', ['tab' => 'market']);
addfield('title', $_SESSION['camp']['currencyname'].' capping', '', ['tab' => 'market']);
addfield('number', 'Maximum '.$_SESSION['camp']['currencyname'].' per adult', 'dropcapadult', ['tab' => 'market', 'width' => 2]);
addfield('number', 'Maximum '.$_SESSION['camp']['currencyname'].' per child', 'dropcapchild', ['tab' => 'market', 'width' => 2]);
addfield('line', '', '', ['tab' => 'market']);
addfield('title', 'Schedule', '', ['tab' => 'market']);
addfield('date', 'Daily start time', 'schedulestart', ['tab' => 'market', 'date' => false, 'time' => true]);
addfield('date', 'Daily end', 'schedulestop', ['tab' => 'market', 'date' => false, 'time' => true]);
addfield('select', 'Length of timeslots', 'scheduletimeslot', ['tab' => 'market', 'multiple' => false, 'options' => [
    ['value' => '3', 'label' => '3 hours'],
    ['value' => '2', 'label' => '2 hours'],
    ['value' => '1', 'label' => '1 hour'],
    ['value' => '0.5', 'label' => '30 minutes'],
    ['value' => '0.25', 'label' => '15 minutes'],
]]);
addfield('line', '', '', ['tab' => 'market']);
addfield('checkbox', 'Include lunch break?', 'schedulebreak', ['tab' => 'market']);
addfield('date', 'Lunch time', 'schedulebreakstart', ['tab' => 'market', 'date' => false, 'time' => true]);
addfield('select', 'Lunch duration', 'schedulebreakduration', ['tab' => 'market', 'multiple' => false, 'options' => [
    ['value' => '0.5', 'label' => '30 minutes'],
    ['value' => '1', 'label' => '1 hour'],
    ['value' => '1.5', 'label' => '1,5 hour'],
    ['value' => '2', 'label' => '2 hours'],
]]);

addfield('number', 'Max duration to rent a bicycle', 'bicyclerenttime', ['tab' => 'bicycle']);
addfield('date', 'Closing time', 'bicycle_closingtime', ['tab' => 'bicycle', 'date' => false, 'time' => true]);
addfield('date', 'Closing time on Saturday', 'bicycle_closingtime_saturday', ['tab' => 'bicycle', 'date' => false, 'time' => true]);

addfield('checkbox', 'Do you give out extraportions?', 'extraportion', ['tab' => 'food']);
addfield('number', 'Maximum '.$_SESSION['camp']['currencyname'].' for food per adult', 'maxfooddrops_adult', ['tab' => 'food']);
addfield('number', 'Maximum '.$_SESSION['camp']['currencyname'].' for food per child', 'maxfooddrops_child', ['tab' => 'food']);

// place the form elements and data in the template
$cmsmain->assign('data', $data);
$cmsmain->assign('formelements', $formdata);
$cmsmain->assign('formbuttons', $formbuttons);
