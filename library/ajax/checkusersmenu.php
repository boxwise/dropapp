<?php

$return = array("success" => TRUE, "message" => "Test");
try {
    $showusermenu = db_value('
    SELECT COUNT(g.id)
    FROM cms_usergroups AS g 
    LEFT JOIN cms_usergroups_levels AS l ON l.id = g.userlevel
    WHERE g.organisation_id = ' . $_SESSION['organisation']['id'] . (!$_SESSION['user']['is_admin'] ? ' AND l.level < ' . intval($_SESSION['usergroup']['userlevel']) : ''));
    $return = array("success" => TRUE, 'showusermenu' => ($showusermenu? true :false));
} catch (Exception $e) {
    $return = array("success" => FALSE, 'message' => $e->getMessage());
}

echo json_encode($return);
