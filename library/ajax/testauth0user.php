<?php

    // get user info by email
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
        $db_user = db_row('SELECT * FROM cms_users WHERE email = :email OR (deleted IS NOT NULL AND email LIKE :deletedEmail)', ['email' => $email, 'deletedEmail' => $email.'.deleted%']);
        $auth_user = getAuth0UserByEmail(preg_match('/\.deleted\.\d+/', $email) ? preg_replace('/\.deleted\.\d+/', '', $email) : $email);

        if (!$db_user && !$auth_user) {
            echo 'true';
        } elseif ($db_user && $auth_user) {
            $auth0User = json_decode($auth_user);
            $validation_result = [];
            array_push($validation_result, ($auth0User[0]->identities[0]->user_id == $db_user['id']) ? 'true' : 'false');
            array_push($validation_result, ($auth0User[0]->name == $db_user['naam']) ? 'true' : 'false');
            array_push($validation_result, ($auth0User[0]->app_metadata->is_god == $db_user['is_admin']) ? 'true' : 'false');
            array_push($validation_result, ($auth0User[0]->app_metadata->usergroup_id == $db_user['cms_usergroups_id']) ? 'true' : 'false');

            if ($db_user['valid_firstday'] && '0000-00-00' != $db_user['valid_firstday']) {
                array_push($validation_result, (!empty($auth0User[0]->app_metadata->valid_firstday) && $auth0User[0]->app_metadata->valid_firstday == $db_user['valid_firstday']) ? 'true' : 'false');
            }

            if ($db_user['valid_lastday'] && '0000-00-00' != $db_user['valid_lastday']) {
                array_push($validation_result, (!empty($auth0User[0]->app_metadata->valid_lastday) && $auth0User[0]->app_metadata->valid_lastday == $db_user['valid_lastday']) ? 'true' : 'false');
            }

            if ('0000-00-00 00:00:00' != $db_user['deleted'] && !is_null($db_user['deleted'])) {
                array_push($validation_result, (!empty($auth0User[0]->app_metadata->last_blocked_date) && $auth0User[0]->app_metadata->last_blocked_date == $db_user['deleted']) ? 'true' : 'false');
                array_push($validation_result, (!empty($auth0User[0]->blocked) && $auth0User[0]->app_metadata->blocked) ? 'true' : 'false');
            }

            echo in_array('false', $validation_result) ? 'false' : 'true';
        } elseif ((!$db_user || $auth_user) && ($db_user || !$auth_user)) {
            echo 'false';
        }
    }
