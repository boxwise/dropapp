<?php

$table = 'camps';
$action = 'base_settings';

if ($_POST && authorize('manage_base_settings', 4)) {
    db_transaction(function () use ($table) {
        $_POST['id'] = $_SESSION['camp']['id'];
        $handler = new formHandler($table);

        $savekeys = [
            'familyidentifier',
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
            'delete_inactive_users',
            'daystokeepdeletedpersons',
            'adult_age',
            'currencyname',
            'dropsperadult',
            'dropsperchild',
            'resettokens',
        ];
        $id = $handler->savePost($savekeys);

        $_SESSION['camp'] = getcampdata($_SESSION['camp']['id']);
    });

    redirect('?action=start');
}

$data = db_row('SELECT * FROM camps WHERE id = :id', ['id' => $_SESSION['camp']['id']]);

// open the template
$cmsmain->assign('include', 'cms_form.tpl');

// put a title above the form
$cmsmain->assign('title', 'Base Settings');

$tabs['beneficiaries'] = 'Beneficiaries';
$tabs['market'] = 'Free Shop';
$cmsmain->assign('tabs', $tabs);

// Beneficiaries
addfield('text', 'Name of beneficiary identifier', 'familyidentifier', ['tab' => 'beneficiaries', 'tooltip' => 'Each beneficiary or family has usually an id or address like an ID from camp mgmt. or the number of the container they live in. How should we name it?']);
addfield('checkbox', 'Do you track if your beneficiaries are officially registered?', 'beneficiaryisregistered', ['tab' => 'beneficiaries']);
addfield('checkbox', 'Can your beneficiaries be volunteers in your organisation?', 'beneficiaryisvolunteer', ['tab' => 'beneficiaries']);
addfield('checkbox', 'Record email for beneficiaries?', 'email_enabled', [
    'tab' => 'beneficiaries',
    'tooltip' => 'Allow users to enter an email address for the beneficiary. This field is optional and can be used for communication purposes.',
]);
addfield('checkbox', 'Record phone number for beneficiaries?', 'phone_enabled', [
    'tab' => 'beneficiaries',
    'tooltip' => 'Allow users to enter a phone number for the beneficiary. This field is optional and can be used for direct communication.',
]);
addfield('line', '', '', ['tab' => 'beneficiaries']);
addfield('subtitle', 'Custom Text Field 1', '', ['tab' => 'beneficiaries', 'tooltip' => 'If the checkbox is enabled, a text field will be added to the beneficiary form with the provided label.']);
addfield('checkbox', 'Enable Field?', 'additional_field1_enabled', [
    'tab' => 'beneficiaries',
    'size' => 2,
    'onchange' => 'conditionalToggle("additional_field1_enabled", "additional_field1_label")',
]);
addfield('text', 'Label', 'additional_field1_label', [
    'tab' => 'beneficiaries',
    'disabled' => $data['additional_field1_enabled'] ? false : true,
    'required' => $data['additional_field1_enabled'],
    'tooltip' => 'Provide a label for this field (e.g., "Family Size" or "Occupation").',
]);
addfield('line', '', '', ['tab' => 'beneficiaries']);
addfield('subtitle', 'Custom Text Field 2', '', ['tab' => 'beneficiaries', 'tooltip' => 'If the checkbox is enabled, a text field will be added to the beneficiary form with the provided label.']);
addfield('checkbox', 'Enable Field?', 'additional_field2_enabled', [
    'tab' => 'beneficiaries',
    'onchange' => 'conditionalToggle("additional_field2_enabled", "additional_field2_label")',
    'checked' => $data['additional_field2_enabled'],
]);
addfield('text', 'Label', 'additional_field2_label', [
    'tab' => 'beneficiaries',
    'disabled' => $data['additional_field2_enabled'] ? false : true,
    'required' => $data['additional_field2_enabled'],
    'tooltip' => 'Provide a label for the second custom field (e.g., "Living Area" or "Notes").',
]);
addfield('line', '', '', ['tab' => 'beneficiaries']);
addfield('subtitle', 'Custom Number Field', '', ['tab' => 'beneficiaries', 'tooltip' => 'If the checkbox is enabled, a number field will be added to the beneficiary form with the provided label.']);
addfield('checkbox', 'Enable Field?', 'additional_field3_enabled', [
    'tab' => 'beneficiaries',
    'onchange' => 'conditionalToggle("additional_field3_enabled", "additional_field3_label")',
]);
addfield('text', 'Label', 'additional_field3_label', [
    'tab' => 'beneficiaries',
    'disabled' => $data['additional_field3_enabled'] ? false : true,
    'required' => $data['additional_field3_enabled'],
    'tooltip' => 'Provide a label for the number field (e.g., "Number of Family Members").',
]);
addfield('line', '', '', ['tab' => 'beneficiaries']);
addfield('subtitle', 'Custom Date Field', '', ['tab' => 'beneficiaries', 'tooltip' => 'If the checkbox is enabled, a date field will be added to the beneficiary form with the provided label.']);
addfield('checkbox', 'Enable Field?', 'additional_field4_enabled', [
    'tab' => 'beneficiaries',
    'onchange' => 'conditionalToggle("additional_field4_enabled", "additional_field4_label")',
]);

addfield('text', 'Label', 'additional_field4_label', [
    'tab' => 'beneficiaries',
    'disabled' => $data['additional_field4_enabled'] ? false : true,
    'required' => $data['additional_field4_enabled'],
    'tooltip' => 'Provide a label for the date field (e.g., "Next Visit Date").',
]);

addfield('number', 'Days to deactivate inactive beneficiaries', 'delete_inactive_users', ['tab' => 'market', 'width' => 2, 'tooltip' => 'Beneficiaries without activity in Boxtribute will be deactivated. Deactivated beneficiaries will remain visible in the Deactivated tab in the Beneficiaries page.']);
addfield('number', 'Days to keep deactivated beneficiaries', 'daystokeepdeletedpersons', ['tab' => 'market', 'width' => 2, 'tooltip' => 'Deactivate beneficiaries will remain visible in the Deactivated tab in the beneficiaries page and will be completely deleted after a while. Here you can define how long they will remain in the Deactivated list.']);
addfield('number', 'Adult age', 'adult_age', ['tab' => 'market', 'width' => 2, 'tooltip' => 'For some functions we distinct between children and adults. Fill in here the lowest age considered adult for this base.']);
addfield('text', 'Currency name', 'currencyname', ['tab' => 'market', 'required' => true, 'width' => 2, 'tooltip' => 'Will get active after first page reload']);
addfield('line', '', '', ['tab' => 'market']);
addfield('title', 'Default '.$_SESSION['camp']['currencyname'].' per cycle', '', ['tab' => 'market']);
addfield('number', $_SESSION['camp']['currencyname'].' per adult', 'dropsperadult', ['tab' => 'market', 'width' => 2]);
addfield('number', $_SESSION['camp']['currencyname'].' per child', 'dropsperchild', ['tab' => 'market', 'width' => 2]);
addfield('checkbox', 'Reset tokens on cycle restart', 'resettokens', ['tab' => 'market', 'tooltip' => 'If you distribute tokens to a person, first all tokens of the beneficiary are reset to 0 before new ones are distributed.']);

// place the form elements and data in the template
$cmsmain->assign('data', $data);
$cmsmain->assign('formelements', $formdata);
$cmsmain->assign('formbuttons', $formbuttons);
