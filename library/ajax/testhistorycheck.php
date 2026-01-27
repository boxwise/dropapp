<?php

// allowed databases
$devdbs = ['dropapp_dev', 'dropapp_staging'];
// confirmed testusers
$testusers = ['admin@admin.co', 'madmin@admin.co', 'coordinator@coordinator.co', 'user@user.co'];

$return = [];

// Test if user is a testusers and the database is a dev database
if (!(in_array($settings['db_database'], $devdbs) && in_array($_SESSION['user']['email'], $testusers))) {
    $msg = 'You do not have access to check test data!';
    trigger_error($msg, E_USER_ERROR);

    echo json_encode(['error' => 'No permission']);
} else {
    $recordId = $_POST['record_id'];
    $tablename = $_POST['tablename'];
    $expectedChange = $_POST['expected_change'];

    // Query history table for matching record
    $historyEntries = db_array(
        'SELECT * FROM history WHERE tablename = :tablename AND record_id = :record_id AND changes LIKE :changes ORDER BY changedate DESC',
        [
            'tablename' => $tablename,
            'record_id' => $recordId,
            'changes' => '%'.$expectedChange.'%',
        ]
    );

    if (count($historyEntries) > 0) {
        echo json_encode(['found' => true, 'count' => count($historyEntries), 'entries' => $historyEntries]);
    } else {
        echo json_encode(['found' => false, 'count' => 0]);
    }
}
