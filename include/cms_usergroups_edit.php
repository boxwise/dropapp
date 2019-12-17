<?php

$table = 'cms_usergroups';
$action = 'cms_usergroups_edit';

if ($_SESSION['user']['is_admin'] || $_SESSION['usergroup']['userlevel'] > db_value('SELECT MIN(level) FROM cms_usergroups_levels')) {
    if ($_POST) {
        if ($_SESSION['user']['is_admin'] || ($_SESSION['usergroup']['userlevel'] > db_value('SELECT level FROM cms_usergroups_levels WHERE id = :id', ['id' => $_POST['userlevel']]))) {
            $_POST['organisation_id'] = $_SESSION['organisation']['id'];

            $handler = new formHandler($table);

            $savekeys = ['label', 'allow_laundry_startcycle', 'allow_laundry_block', 'allow_borrow_adddelete', 'userlevel', 'organisation_id'];
            $id = $handler->savePost($savekeys);
            $handler->saveMultiple('camps', 'cms_usergroups_camps', 'cms_usergroups_id', 'camp_id');
            $handler->saveMultiple('cms_functions', 'cms_usergroups_functions', 'cms_usergroups_id', 'cms_functions_id');

            redirect('?action='.$_POST['_origin']);
        } else {
            throw new Exception('Naughty boy!');
        }
    }

    $data = db_row('SELECT * FROM '.$table.' WHERE id = :id', ['id' => $id]);

    if (!$id) {
        $data['visible'] = 1;
    }

    // open the template
    $cmsmain->assign('include', 'cms_form.tpl');

    // put a title above the form
    $cmsmain->assign('title', 'User group');

    $tabs['general'] = 'General';
    $tabs['bicycle'] = 'Bicycles';
    $tabs['laundry'] = 'Laundry';
    $cmsmain->assign('tabs', $tabs);

    // Specify when tabs should be hidden
    $hidden = db_row('
		SELECT MAX(c.bicycle) AS bicycle, MAX(c.laundry) AS laundry
		FROM cms_usergroups ug
		LEFT JOIN cms_usergroups_camps ugc ON ug.id = ugc.cms_usergroups_id
		LEFT JOIN camps c ON ugc.camp_id=c.id
		WHERE ug.id=:id AND (NOT ug.deleted OR ug.deleted IS NULL)', ['id' => $id]);
    $hiddentabs['bicycle'] = !$hidden['bicycle'];
    $hiddentabs['laundry'] = !$hidden['laundry'];
    $cmsmain->assign('hiddentabs', $hiddentabs);

    addfield('text', 'Name', 'label', ['tab' => 'general']);

    addfield('select', 'Level', 'userlevel', ['tab' => 'general', 'required' => true, 'query' => '
		SELECT id AS value, label 
		FROM cms_usergroups_levels 
		WHERE level < '.intval($_SESSION['usergroup']['userlevel']).' OR '.$_SESSION['user']['is_admin'].'
		ORDER BY level']);

    addfield('select', 'Available bases', 'camps', ['tab' => 'general', 'multiple' => true, 'query' => '
		SELECT a.id AS value, a.name AS label, IF(x.cms_usergroups_id IS NOT NULL, 1,0) AS selected 
		FROM camps AS a 
		LEFT OUTER JOIN cms_usergroups_camps AS x ON a.id = x.camp_id AND x.cms_usergroups_id = '.intval($id).'
		LEFT OUTER JOIN cms_usergroups_camps AS y ON a.id = y.camp_id
		WHERE (NOT a.deleted OR a.deleted IS NULL) AND a.organisation_id = '.$_SESSION['organisation']['id'].($_SESSION['user']['is_admin'] ? '' : ' AND y.cms_usergroups_id = '.$_SESSION['usergroup']['id']).'
		GROUP BY a.id
		ORDER BY seq']);

    addfield('select', $translate['cms_users_access'], 'cms_functions', ['tab' => 'general', 'multiple' => true, 'query' => '
	SELECT 
		a.id AS value, a.title_en AS label, IF(x.cms_usergroups_id IS NOT NULL, 1,0) AS selected 
	FROM cms_functions AS a 
	LEFT OUTER JOIN cms_usergroups_functions AS x ON a.id = x.cms_functions_id AND x.cms_usergroups_id = '.intval($id).'
	LEFT OUTER JOIN cms_usergroups_functions AS y ON a.id = y.cms_functions_id 
	WHERE NOT a.adminonly AND NOT a.allusers AND a.parent_id IS NOT NULL AND a.visible'.($_SESSION['user']['is_admin'] ? '' : ' AND y.cms_usergroups_id = '.$_SESSION['usergroup']['id']).'
	GROUP BY a.id
	ORDER BY a.title_en, seq']);

    // addfield('checkbox', 'Users can add or remove Bicycle/sport items', 'allow_borrow_adddelete', array('tab' => 'bicycle'));
    // addfield('checkbox', 'Users can start a new laundry cycle', 'allow_laundry_startcycle', array('tab' => 'laundry'));
    // addfield('checkbox', 'Users can block beneficiaries from using the laundry', 'allow_laundry_block', array('tab' => 'laundry'));

    addfield('line', '', '', ['aside' => true]);
    addfield('created', 'Created', 'created', ['aside' => true]);

    // place the form elements and data in the template
    $cmsmain->assign('data', $data);
    $cmsmain->assign('formelements', $formdata);
    $cmsmain->assign('formbuttons', $formbuttons);
} else {
    throw new Exception('You do not have access to this menu. Please ask your admin to change this!');
}
