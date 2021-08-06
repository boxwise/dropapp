<?php

db_transaction(function () {
    db_query('UPDATE cms_users SET deleted = NOW(), email = CONCAT(email,".deleted.",id) WHERE id = :id', ['id' => $_POST['cms_user_id']]);
    updateAuth0UserFromDb($_POST['cms_user_id']);
});

simpleSaveChangeHistory('cms_users', $_POST['cms_user_id'], 'Record deleted without undelete');
// when a user deactive its account we need to ensure that user logged out immediately and then redirected to Auth0 login page
global $settings;
logout();
$return = ['success' => true, 'message' => 'You successfully deactivated your account!', 'redirect' => 'https://'.$settings['auth0_domain'].'/v2/logout?client_id='.$settings['auth0_client_id'].'&returnTo='.urlencode($settings['auth0_redirect_uri'])];

echo json_encode($return);
