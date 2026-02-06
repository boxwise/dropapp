<?php

db_transaction(function () {
    $user_id = $_SESSION['user']['id'];
    // Only append .deleted suffix if the email doesn't already have it
    // This prevents double-deletion if the query is somehow executed twice
    // Pattern checks for .deleted.<digits> after the @ symbol (e.g., user@domain.com.deleted.123)
    db_query('UPDATE cms_users SET deleted = NOW(), email = CONCAT(email,".deleted.",id) WHERE id = :id AND (NOT deleted OR deleted IS NULL) AND email NOT REGEXP "@.*\.deleted\.[0-9]+$"', ['id' => $user_id]);
    updateAuth0UserFromDb($user_id);
    simpleSaveChangeHistory('cms_users', $user_id, 'Record deleted without undelete');
});

// when a user deactive its account we need to ensure that user logged out immediately and then redirected to Auth0 login page
global $settings;
logout();
$return = ['success' => true, 'message' => 'You successfully deactivated your account!', 'redirect' => 'https://'.$settings['auth0_domain'].'/v2/logout?client_id='.$settings['auth0_client_id'].'&returnTo='.urlencode((string) $settings['auth0_redirect_uri'])];

echo json_encode($return);
