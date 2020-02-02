<?php

try {
    $showusermenu = db_value('
    SELECT COUNT(g.id)
    FROM cms_usergroups AS g 
    LEFT JOIN cms_usergroups_levels AS l ON l.id = g.userlevel
    WHERE g.organisation_id = '.$_SESSION['organisation']['id'].(!$_SESSION['user']['is_admin'] ? ' 
        AND (NOT g.deleted OR g.deleted IS NULL)
        AND l.level < '.intval($_SESSION['usergroup']['userlevel']) : ''));
    $return = ['success' => true, 'showusermenu' => ($showusermenu ? true : false)];
} catch (Exception $e) {
    $msg = $e->getMessage();
    $return = ['success' => false, 'message' => $msg];
    trigger_error($msg);
}

echo json_encode($return);
