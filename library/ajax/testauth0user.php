<?php

// get user info by email
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    if (isUserInSyncWithAuth0ByEmail($email)) {
        echo 'true';
    } else {
        echo 'false';
    }
}
