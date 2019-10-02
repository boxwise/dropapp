<?php
    if (!array_key_exists('HTTP_X_APPENGINE_CRON', $_SERVER)) {
        throw new Exception('Not called from AppEngine cron service');
    }
    $permittedDatabases = ['dropapp_demo', 'dropapp_staging'];
    if (!in_array($settings['db_database'], permittedDatabases)) {
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
            'demo' => [
                'adapter' => 'mysql',
                'unix_socket' => $settings['db_socket'],
                'name' => $settings['db_database'],
                'user' => $settings['db_user'],
                'pass' => $settings['db_pass'],
                'charset' => 'utf8',
                'mysql_attr_init_command' => "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO'",
            ],
        ],
    ];
    $config = new Config($configArray);
    $manager = new Manager($config, new StringInput(' '), new NullOutput());
    echo 'Resetting seed data';
    $manager->seed('demo');
    echo 'Successfully reset data';
