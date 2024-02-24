<?php

$table = 'translate';
$hasdescription = db_fieldexists($table, 'description');

if ($_POST) {
    $handler = new formHandler($table);
    // $handler->debug = true;

    $savekeys = ['code', 'hidden', 'category_id', 'type'];
    if ($hasdescription) {
        $savekeys[] = 'description';
    }

    foreach ($settings['languages'] as $language) {
        if ($language['code'] && db_fieldexists('translate', $language['code'])) {
            $savekeys[] = $language['code'];
        }
    }
    $handler->savePost($savekeys);

    if ('submitandedit' == $_POST['__action']) {
        redirect('?action='.$action.'&origin='.$_POST['_origin'].'&id='.$id);
    }
    redirect('?action='.$_POST['_origin']);
}

// collect data for the form
$data = db_row('SELECT * FROM '.$table.' WHERE id = :id', ['id' => $id]);

// open the template
$cmsmain->assign('include', 'cms_form.tpl');

// put a title above the form
$cmsmain->assign('title', ucfirst($translate['cms_translate']));

addfield('text', $translate['cms_translate_code'], 'code', ['required' => true, 'readonly' => !$_SESSION['user']['is_admin']]);

if ($_SESSION['user']['is_admin']) {
    addfield(
        'select',
        $translate['cms_translate_type'],
        'type',
        ['width' => 5, 'required' => true, 'options' => [
            ['value' => 'text', 'label' => $translate['cms_field_text']],
            ['value' => 'textarea', 'label' => $translate['cms_field_textarea']],
        ]]
    );
    // addfield('select',$translate['cms_settings_category'],'category_id',array('required'=>true, 'width'=>5,'query'=>'SELECT id AS value, name AS label FROM translate_categories ORDER BY id'));
    addfield('checkbox', $translate['cms_settings_hidden'], 'hidden');
} else {
    addfield('hidden', '', 'description');
    addfield('hidden', '', 'hidden');
    addfield('hidden', '', 'category_id');
    addfield('hidden', '', 'type');
}

addfield('line');

foreach ($settings['languages'] as $language) {
    match ($data['type']) {
        'text' => addfield('text', $language['name'], $language['code']),
        default => addfield('textarea', $language['name'], $language['code']),
    };
}

addfield('line');
if ($hasdescription) {
    addfield('text', $translate['cms_translate_description'], 'description', ['readonly' => !$_SESSION['user']['is_admin']]);
}

addfield('created', '', '', ['aside' => true]);
if ($id) {
    addformbutton('submitandedit', $translate['cms_form_save']);
}

$cmsmain->assign('data', $data);
$cmsmain->assign('formelements', $formdata);
$cmsmain->assign('formbuttons', $formbuttons);
