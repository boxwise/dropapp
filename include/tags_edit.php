<?php

    $action = 'tags_edit';
    $table = 'tags';

    if ($_POST) {
        $_POST['camp_id'] = $_SESSION['camp']['id'];

        $handler = new formHandler($table);

        $savekeys = ['label', 'color', 'camp_id'];
        $id = $handler->savePost($savekeys);

        redirect('?action='.$_POST['_origin']);
    }

    $cmsmain->assign('title', 'Tags');

    $data = db_row('
        SELECT 
            * 
        FROM 
            tags
        WHERE 
            id = :id 
            AND deleted IS NULL ', ['id' => $id]);

    addfield('text', 'Name', 'label');
    addfield('color', 'Color', 'color');
    addfield('line', '', '', ['aside' => true]);
    addfield('created', 'Created', 'created', ['aside' => true]);

    $cmsmain->assign('include', 'cms_form.tpl');
    $cmsmain->assign('data', $data);
    $cmsmain->assign('formelements', $formdata);
    $cmsmain->assign('formbuttons', $formbuttons);
