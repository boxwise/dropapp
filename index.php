<?php

    $login = false;
    $ajax = false;
    $mobile = false;

    require_once 'library/core.php';
    date_default_timezone_set('Europe/Athens');
    db_query('SET time_zone = "+'.(date('Z') / 3600).':00"');

    // action set by POST will override GET
    $action = (isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : 'start'));
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if ('logout' == $action) {
        logout();
    }

    $cmsmain = new Zmarty();

    // Fill the organisation menu
    $cmsmain->assign('currentOrg', $_SESSION['organisation']);
    if ($_SESSION['user']['is_admin']) {
        $cmsmain->assign('organisations', organisationlist());
    }
    // This fills the camp menu in the top bar (only if the user has access to more than 1 camp
    $cmsmain->assign('camps', camplist());
    $cmsmain->assign('currentcamp', $_SESSION['camp']);
    $cmsmain->assign('campaction', strpos($action, '_edit') ? substr($action, 0, -5) : $action);
    $cmsmain->assign('haswarehouse', db_value('SELECT id FROM locations WHERE camp_id = '.intval($_SESSION['camp']['id']).' LIMIT 1 '));

    $cmsmain->assign('menu', CMSmenu());

    // checks if the requested action is allowed for the user's usergroup and camp
    $allowed = db_numrows('SELECT f.id, f.title_en, IF(f2.parent_id IS NOT NULL,"3","2") FROM cms_functions AS f 
LEFT OUTER JOIN cms_usergroups_functions AS uf ON uf.cms_functions_id = f.id
LEFT OUTER JOIN cms_functions_camps AS fc ON fc.cms_functions_id = f.id
LEFT OUTER JOIN cms_functions AS f2 ON f.parent_id = f2.id
LEFT OUTER JOIN cms_usergroups_functions AS uf2 ON uf2.cms_functions_id = f2.id
LEFT OUTER JOIN cms_functions_camps AS fc2 ON fc2.cms_functions_id = f2.id
WHERE 
(f.include = :action OR CONCAT(f.include,"_edit") = :action OR CONCAT(f.include,"_deactivated") = :action OR CONCAT(f.include,"_trash") = :action OR CONCAT(f.include,"_confirm") = :action OR CONCAT(f.include,"_export") = :action OR CONCAT(f.include,"_expired") = :action)
AND (f.allusers OR (f2.parent_id IS NULL AND uf.cms_usergroups_id = :usergroup AND (fc.camps_id = :camp_id OR f.allcamps)) OR f2.allusers OR (f2.parent_id IS NOT NULL AND uf2.cms_usergroups_id = :usergroup AND (fc2.camps_id = :camp_id OR f2.allcamps)))
', ['usergroup' => $_SESSION['usergroup']['id'], 'camp_id' => $_SESSION['camp']['id'], 'action' => $action]);

    // if the action is allowed or if the user is a system admin, we load it
    if ($allowed || $_SESSION['user']['is_admin']) {
        @include 'include/'.$action.'.php';
    }

    $cmsmain->assign('action', $action);
    $cmsmain->assign('identifyUserToAnalytics', shouldIdentifyUserToAnalytics());
    $cmsmain->display('cms_index.tpl');
