<?php

    // get user info by email
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
        $dbUser = db_row('SELECT * FROM cms_users WHERE email = :email OR (deleted IS NOT NULL AND email LIKE :deletedEmail)', ['email' => $email, 'deletedEmail' => $email.'.deleted%']);
        $auth0UserUnformatted = getAuth0UserByEmail(preg_match('/\.deleted\.\d+/', $email) ? preg_replace('/\.deleted\.\d+/', '', $email) : $email);

        if (!$dbUser && !$auth0UserUnformatted) {
            echo 'true';
        } elseif ($dbUser && $auth0UserUnformatted) {
            $auth0User = json_decode($auth0UserUnformatted);
            $validationResult = [];
            array_push($validationResult, ($auth0User[0]->identities[0]->user_id == $dbUser['id']) ? 'true' : 'false');
            array_push($validationResult, ($auth0User[0]->name == $dbUser['naam']) ? 'true' : 'false');
            array_push($validationResult, ($auth0User[0]->app_metadata->is_god == $dbUser['is_admin']) ? 'true' : 'false');
            array_push($validationResult, ($auth0User[0]->app_metadata->usergroup_id == $dbUser['cms_usergroups_id']) ? 'true' : 'false');

            if ($dbUser['valid_firstday'] && '0000-00-00' != $dbUser['valid_firstday']) {
                array_push($validationResult, (!empty($auth0User[0]->app_metadata->valid_firstday) && $auth0User[0]->app_metadata->valid_firstday == $dbUser['valid_firstday']) ? 'true' : 'false');
            }

            if ($dbUser['valid_lastday'] && '0000-00-00' != $dbUser['valid_lastday']) {
                array_push($validationResult, (!empty($auth0User[0]->app_metadata->valid_lastday) && $auth0User[0]->app_metadata->valid_lastday == $dbUser['valid_lastday']) ? 'true' : 'false');
            }

            if ('0000-00-00 00:00:00' != $dbUser['deleted'] && !is_null($dbUser['deleted'])) {
                array_push($validationResult, (!empty($auth0User[0]->app_metadata->last_blocked_date) && $auth0User[0]->app_metadata->last_blocked_date == $dbUser['deleted']) ? 'true' : 'false');
                array_push($validationResult, (!empty($auth0User[0]->blocked) && $auth0User[0]->blocked) ? 'true' : 'false');
            }

            echo in_array('false', $validationResult) ? 'false' : 'true';
        } elseif ((!$dbUser || $auth0UserUnformatted) && ($dbUser || !$auth0UserUnformatted)) {
            echo 'false';
        }
    }
