<?php

    $table = 'cms_functions';

    if ($_POST) {
        $handler = new formHandler($table);
        //$handler->debug = true;
        $keys = ['title_en', 'include', 'parent_id', 'adminonly', 'visible', 'allusers', 'allcamps'];
        $handler->savePost($keys, ['parent_id']);

        redirect('?action='.$_POST['_origin']);
    }

    // collect data for the form
    $data = db_row('SELECT * FROM '.$table.' WHERE id = :id', ['id' => $id]);

    // open the template
    $cmsmain->assign('include', 'cms_form.tpl');

    // put a title above the form
    $cmsmain->assign('title', $translate['cms_function']);

    // define tabs
    $title = (db_fieldexists('cms_functions', 'title_'.$lan) ? 'title_'.$lan : 'title');

    addfield('select', 'Onderdeel van', 'parent_id', ['required' => true, 'formatlist' => 'formatparent', 'multiple' => false, 'placeholder' => 'Maak een keuze', 'options' => getParentarray($table, 0, 1, $title)]);

    addfield('text', $translate['cms_function'], 'title_en', ['required' => true]);
    addfield('text', $translate['cms_function_include'], 'include');

    addfield('checkbox', 'This item is visible in the menu', 'visible');
    addfield('checkbox', 'Only available for gods', 'adminonly');
    addfield('checkbox', 'Available for all camps', 'allcamps');
    addfield('checkbox', 'Available for all usergroups', 'allusers');

    addfield('created', 'Gemaakt', 'created', ['aside' => true]);

    $cmsmain->assign('data', $data);
    $cmsmain->assign('formelements', $formdata);
