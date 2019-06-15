<?php
    require_once __DIR__.'/library/config.php';
    require_once __DIR__.'/vendor/autoload.php';
    if (!$_SERVER['X-Appengine-Cron'])
        return;
    use Phinx\Config\Config;
    use Phinx\Migration\Manager;
    use Symfony\Component\Console\Input\StringInput;
    use Symfony\Component\Console\Output\NullOutput;

    $configArray = [
        'paths' => [
            'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
        ],
        'environments' => [
            'demo' => [
                'adapter' => 'mysql',
                'host' => $settings['db_host'],
                'name' => 'dropapp_demo',
                'user' => $settings["db_user"],
                'pass' => $settings["db_password"],
                'port' => 3306,
                'charset' => 'utf8',
                'mysql_attr_init_command' => "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO'",
            ]
        ]
    ];
    $config = new Config($configArray);
    $manager = new Manager($config, new StringInput(' '), new NullOutput());
    echo "Resetting seed data\n";
    $manager->seed('demo');
    echo "Reset";
