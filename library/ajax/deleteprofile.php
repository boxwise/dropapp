<?php

db_query('UPDATE cms_users SET deleted = NOW(), email = CONCAT(email,".deleted.",id) WHERE id = :id', array('id'=>$_POST['cms_user_id']));
simpleSaveChangeHistory('cms_users', $_POST['cms_user_id'], 'Record deleted without undelete');
$return = array("success" => True, 'message'=> "You successfully deactivated your account!", 'redirect'=>$settings['rootdir'] . '/login.php');

echo json_encode($return);