<?php

use Google\Cloud\Datastore\DatastoreClient;
use Google\Cloud\Datastore\DatastoreSessionHandler;

if (!array_key_exists('HTTP_X_APPENGINE_CRON', $_SERVER) && 'dropapp_dev' != $settings['db_database']) {
    throw new Exception('Not called from AppEngine cron service');
}

$permittedDatabases = ['dropapp_dev', 'dropapp_demo', 'dropapp_staging', 'dropapp_production'];
if (!in_array($settings['db_database'], $permittedDatabases)) {
    throw new Exception('Not permitting to run fix-double-deleted-emails for '.$settings['db_database']);
}

$bypassAuthentication = true;

require_once 'library/core.php';

// Fix double-deleted email suffixes
// When a user is deleted twice, the email becomes: email.deleted.ID.deleted.ID
// This cron job removes the duplicate suffixes so reactivation works correctly

$result = db_query("
    SELECT id, email
    FROM cms_users
    WHERE email LIKE '%.deleted.%.deleted.%'
");

$fixed_count = 0;
while ($row = db_fetch($result)) {
    $email = $row['email'];
    $id = $row['id'];

    // Remove all .deleted.ID suffixes
    $pattern = '/\.deleted\.\d+/';
    $clean_email = preg_replace($pattern, '', $email);

    // Add back a single .deleted.ID suffix
    $corrected_email = $clean_email.'.deleted.'.$id;

    db_query(
        'UPDATE cms_users SET email = :corrected_email WHERE id = :id',
        ['corrected_email' => $corrected_email, 'id' => $id]
    );

    simpleSaveChangeHistory('cms_users', $id, 'Fixed double-deleted email suffix via cron');

    ++$fixed_count;
}

echo json_encode([
    'success' => true,
    'message' => "Fixed {$fixed_count} email(s) with double-deleted suffixes",
]);
