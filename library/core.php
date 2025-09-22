<?php

use OpenCensus\Trace\Tracer;

Tracer::inSpan(
    ['name' => 'library/core.php'],
    function () use ($bypassAuthentication, $mobile, $ajax) {
        global $settings, $lan, $translate, $menusToActions, $rolesToActions;

        if (!defined('LOADED_VIA_SINGLE_ENTRY_POINT')) {
            throw new Exception('This app must now be running through the single entry point. Is your web server config directing all php traffic to gcloud-entry.php?');
        }
        define('CORE', true);
        session_start();

        // load database library
        require_once 'lib/database.php';

        if (!array_key_exists('upload_dir', $settings)) {
            $settings['upload_dir'] = $_SERVER['DOCUMENT_ROOT'].'/uploads';
        }

        // Override config with environment variables if available
        if (getenv('AUTH0_CLIENT_ID')) {
            $settings['auth0_client_id'] = getenv('AUTH0_CLIENT_ID');
        }
        if (getenv('AUTH0_CLIENT_SECRET')) {
            $settings['auth0_client_secret'] = getenv('AUTH0_CLIENT_SECRET');
        }
        if (getenv('AUTH0_COOKIE_SECRET')) {
            $settings['auth0_cookie_secret'] = getenv('AUTH0_COOKIE_SECRET');
        }
        if (getenv('AUTH0_REDIRECT_URI')) {
            $settings['auth0_redirect_uri'] = getenv('AUTH0_REDIRECT_URI');
        }
        if (getenv('AUTH0_API_ID')) {
            $settings['auth0_api_id'] = getenv('AUTH0_API_ID');
        }
        if (getenv('AUTH0_DB_CONNECTION_ID')) {
            $settings['auth0_db_connection_id'] = getenv('AUTH0_DB_CONNECTION_ID');
        }

        // connect to database
        // Override config with environment variables if available
        if (getenv('MYSQL_HOST')) {
            $settings['db_host'] = getenv('MYSQL_HOST');
        }
        if (getenv('MYSQL_PORT')) {
            $settings['db_port'] = getenv('MYSQL_PORT');
        }
        
        if (array_key_exists('db_socket', $settings)) {
            $db_dsn = 'mysql:dbname='.$settings['db_database'].';unix_socket='.$settings['db_socket'];
        } else {
            $db_dsn = 'mysql:host='.$settings['db_host'].';dbname='.$settings['db_database'];
            if (array_key_exists('db_port', $settings)) {
                $db_dsn .= ';port='.$settings['db_port'];
            }
        }
        db_connect($db_dsn, $settings['db_user'], $settings['db_pass']);

        // set timezone
        date_default_timezone_set('UTC');
        db_query('SET time_zone = "+00:00"');

        // get settings from settings table
        $result = db_query('SELECT code, value FROM cms_settings');
        while ($row = db_fetch($result)) {
            $settings[$row['code']] = $row['value'];
        }

        $locale = db_row('SELECT locale FROM languages WHERE code = :lan', ['lan' => $settings['cms_language']]);
        setlocale(LC_ALL, $locale);
        mb_internal_encoding('UTF-8');

        // load translate library
        require_once 'lib/translate.php';

        // load other libraries
        require_once 'lib/session.php';

        require_once 'lib/tools.php';

        require_once 'lib/mail.php';

        require_once 'lib/csvexport.php';

        // load CMS specific libraries
        require_once 'lib/form.php';

        require_once 'lib/list.php';

        require_once 'lib/formhandler.php';

        // functions that are app specific but need to available globally
        require_once 'functions.php';
        if (!$bypassAuthentication) {
            authenticate($settings, $ajax);
        }
    }
);
