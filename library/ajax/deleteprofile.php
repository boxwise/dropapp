<?php

db_transaction(function () {
    $now = date('Y-m-d H:i:s');
    // Only append .deleted suffix if the email doesn't already have it
    // This prevents double-deletion if the query is somehow executed twice
    // Pattern checks for .deleted.<digits> after the @ symbol (e.g., user@domain.com.deleted.123)
    db_query('UPDATE cms_users SET deleted = :now, email = CONCAT(email,".deleted.",id) WHERE id = :id AND (NOT deleted OR deleted IS NULL) AND email NOT REGEXP "@.*\.deleted\.[0-9]+$"', ['now' => $now, 'id' => $_POST['cms_user_id']]);
    updateAuth0UserFromDb($_POST['cms_user_id']);
    simpleSaveChangeHistory('cms_users', $_POST['cms_user_id'], 'Record deleted without undelete', $now);
});

// when a user deactive its account we need to ensure that user logged out immediately and then redirected to Auth0 login page
global $settings;
logout();
$return = ['success' => true, 'message' => 'You successfully deactivated your account!', 'redirect' => 'https://'.$settings['auth0_domain'].'/v2/logout?client_id='.$settings['auth0_client_id'].'&returnTo='.urlencode((string) $settings['auth0_redirect_uri'])];

echo json_encode($return);
