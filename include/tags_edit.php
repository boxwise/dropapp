<?php

$action = 'tags_edit';
$table = 'tags';

if ($_POST) {
    db_transaction(function () use ($table) {
        $_POST['camp_id'] = $_SESSION['camp']['id'];

        $handler = new formHandler($table);

        $savekeys = ['label', 'color', 'description', 'camp_id', 'type'];
        $id = $handler->savePost($savekeys);
        // remove tags based on the type
        // related trello https://trello.com/c/XjNwO3sL
        if ('All' !== $_POST['type'][0]) {
            $objectType = ('People' === $_POST['type'][0]) ? 'Stock' : 'People';
            db_query('DELETE FROM tags_relations WHERE tag_id = :tagId AND object_type = :objectType ', ['tagId' => $id, 'objectType' => $objectType]);
        }
    });

    redirect('?action='.$_POST['_origin']);
}

$cmsmain->assign('title', 'Tags');
// display total number of tags based on the object type
$data = db_row('SELECT 
                        tags.*,
                        SUM(IF(tags_relations.object_type = "Stock",
                            1,
                            0)) AS boxes_count,
                        SUM(IF(tags_relations.object_type = "People",
                            1,
                            0)) AS beneficiaries_count
                    FROM
                        tags
                            LEFT JOIN
                        tags_relations ON tags_relations.tag_id = tags.id
                    WHERE
                        tags.deleted IS NULL AND tags.id = :id
                    GROUP BY tags.id ', ['id' => $id]);

$onchange = sprintf('checkTags(%d);', $id);

addfield('text', 'Name', 'label', ['required' => true]);
addfield('color', 'Color', 'color', ['required' => true]);
// choose the object type that tag can be applied to
addfield('select', 'Apply to', 'type', [
    'testid' => 'tag_type',
    'required' => true,
    'tooltip' => 'Changing tags will remove those already applied based on the selection',
    'onchange' => $onchange,
    'options' => [
        ['value' => 'All', 'label' => 'Boxes + Beneficiaries'],
        ['value' => 'People', 'label' => 'Beneficiaries'],
        ['value' => 'Stock', 'label' => 'Boxes'],
    ],
]);
addfield('textarea', 'Description', 'description');
addfield('created', 'Created', 'created', ['aside' => true]);

$cmsmain->assign('include', 'cms_form.tpl');
$cmsmain->assign('data', $data);
$cmsmain->assign('formelements', $formdata);
$cmsmain->assign('formbuttons', $formbuttons);
