<?php

db_query('UPDATE cms_users SET deleted = NOW(), email = CONCAT(email,".deleted.",id) WHERE id = :id', ['id' => $_POST['cms_user_id']]);
simpleSaveChangeHistory('cms_users', $_POST['cms_user_id'], 'deleted the Record permanently');
$return = ['success' => true, 'message' => 'You successfully deactivated your account!', 'redirect' => '/login.php'];

echo json_encode($return);
