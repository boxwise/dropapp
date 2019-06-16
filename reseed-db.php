<?php
    error_reporting(E_ALL^E_NOTICE);
    ini_set('display_errors',1);

    require_once __DIR__.'/library/config.php';
    require_once __DIR__.'/vendor/autoload.php';
    if (!array_key_exists('HTTP_X_APPENGINE_CRON',$_SERVER))
         throw new Exception("Not called from AppEngine cron service");
         
    use Phinx\Config\Config;
    use Phinx\Migration\Manager;
    use Symfony\Component\Console\Input\StringInput;
    use Symfony\Component\Console\Output\NullOutput;

    $configArray = [
        'paths' => [
            'seeds' => './db/seeds'
        ],
        'environments' => [
            'demo' => [
                'adapter' => 'mysql',
                'unix_socket' => $settings['db_socket'],
                'name' => 'dropapp_demo',
                'user' => $settings["db_user"],
                'pass' => $settings["db_pass"],
                'charset' => 'utf8',
                'mysql_attr_init_command' => "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO'",
            ]
        ]
    ];
    $config = new Config($configArray);
    $manager = new Manager($config, new StringInput(' '), new NullOutput());
    echo("Resetting seed data");
    $manager->seed('demo');
    echo("Successfully reset data");
    