<?php

    if (!array_key_exists('HTTP_X_APPENGINE_CRON', $_SERVER) && 'dropapp_dev' != $settings['db_database']) {
        throw new Exception('Not called from AppEngine cron service');
    }
    $permittedDatabases = ['dropapp_dev', 'dropapp_demo', 'dropapp_staging'];
    if (!in_array($settings['db_database'], $permittedDatabases)) {
        throw new Exception('Not permitting a reset of '.$settings['db_database']);
    }
    use Phinx\Config\Config;
    use Phinx\Migration\Manager;
    use Symfony\Component\Console\Input\StringInput;
    use Symfony\Component\Console\Output\NullOutput;

    $configArray = [
        'paths' => [
            'seeds' => './db/seeds',
        ],
        'environments' => [
            'current' => [
                'adapter' => 'mysql',
                'unix_socket' => $settings['db_socket'],
                'host' => $settings['db_host'],
                'name' => $settings['db_database'],
                'user' => $settings['db_user'],
                'pass' => $settings['db_pass'],
                'charset' => 'utf8',
                'mysql_attr_init_command' => "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO'",
            ],
        ],
    ];
    // don't output anything here, this will ensure
    // any exception will trigger the error page with
    // a 500 status so Google will mark the job as failed
    $config = new Config($configArray);
    $manager = new Manager($config, new StringInput(' '), new NullOutput());
    $manager->seed('current');

    // update Auth0
    $bypassAuthentication = true;
    require_once 'library/core.php';
    $db_users = db_query('SELECT id FROM cms_users;');
    while ($db_user = db_fetch($db_users)) {
        updateAuth0UserFromDb($db_user['id'], $settings['test_pwd']);
        sleep(1);
    }
