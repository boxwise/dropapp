<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddBaseSettingMenu extends AbstractMigration
{
    public function up(): void
    {
        $dbName = $this->fetchRow('SELECT DATABASE();');
        $this->output->writeln("Running upwards migration on database: {$dbName[0]}");

        // add new menu items
        $this->execute("
            INSERT INTO `cms_functions` (`id`, `parent_id`, `title_en`, `include`, `seq`, `alert`, `adminonly`, `visible`, `allusers`, `allcamps`, `action_permissions`) VALUES ('170', '42', 'Base Settings (<span>beta</span>)', 'base_settings', '28', '0', '0', '1', '0', '1', 'manage_base_settings');
        ");

        // enable everywhere except in production
        if ('dropapp_production' != $dbName[0]) {
            // cms_usergroups_functions for Manage Services
            $this->execute("
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('170', '1');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('170', '2');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('170', '10');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('170', '11');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('170', '12');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('170', '15');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('170', '17');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('170', '100000001');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('170', '100000002');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('170', '100000206');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('170', '100000207');
            ");
        } else {
            $this->output->writeln('Only cms_functions is touched since we are in dropapp_production');
        }
    }

    public function down(): void
    {
        $dbName = $this->fetchRow('SELECT DATABASE();');
        $this->output->writeln("Running downwards migration on database: {$dbName[0]}");
        if ('dropapp_production' != $dbName[0]) {
            $this->execute("
                DELETE FROM `cms_functions_camps` WHERE `cms_functions_id` = '170';
            ");
        } else {
            $this->output->writeln('Only cms_functions is touched since we are in dropapp_production');
        }

        $this->execute("
            DELETE FROM `cms_functions` WHERE `id` = '170';
        ");
    }
}
