<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddServiceMenues extends AbstractMigration
{
    public function up(): void
    {
        $dbName = $this->fetchRow('SELECT DATABASE();');
        $this->output->writeln("Running upwards migration on database: {$dbName[0]}");

        // make services subsection visible
        $this->execute("
            UPDATE `cms_functions` SET `visible` = '1' WHERE `id` = '131';
        ");

        // add new menu items
        $this->execute("
            INSERT INTO `cms_functions` (`id`, `parent_id`, `title_en`, `include`, `seq`, `alert`, `adminonly`, `visible`, `allusers`, `allcamps`, `action_permissions`) VALUES ('168', '131', 'Use Service (<span>beta</span>)', 'use_service', '10', '0', '0', '1', '0', '0', 'register_service_usage');
            INSERT INTO `cms_functions` (`id`, `parent_id`, `title_en`, `include`, `seq`, `alert`, `adminonly`, `visible`, `allusers`, `allcamps`, `action_permissions`) VALUES ('169', '131', 'Manage Services (<span>beta</span>)', 'services', '11', '0', '0', '1', '0', '0', 'manage_services');
        ");

        // enable everywhere except in production
        if ('dropapp_production' != $dbName[0]) {
            //cms_functions_camps
            $this->execute("
                INSERT INTO `cms_functions_camps` (`cms_functions_id`, `camps_id`) VALUES ('168', '1');
                INSERT INTO `cms_functions_camps` (`cms_functions_id`, `camps_id`) VALUES ('168', '2');
                INSERT INTO `cms_functions_camps` (`cms_functions_id`, `camps_id`) VALUES ('168', '3');
                INSERT INTO `cms_functions_camps` (`cms_functions_id`, `camps_id`) VALUES ('168', '4');
                INSERT INTO `cms_functions_camps` (`cms_functions_id`, `camps_id`) VALUES ('168', '100000000');
                INSERT INTO `cms_functions_camps` (`cms_functions_id`, `camps_id`) VALUES ('168', '100000001');
                INSERT INTO `cms_functions_camps` (`cms_functions_id`, `camps_id`) VALUES ('169', '1');
                INSERT INTO `cms_functions_camps` (`cms_functions_id`, `camps_id`) VALUES ('169', '2');
                INSERT INTO `cms_functions_camps` (`cms_functions_id`, `camps_id`) VALUES ('169', '3');
                INSERT INTO `cms_functions_camps` (`cms_functions_id`, `camps_id`) VALUES ('169', '4');
                INSERT INTO `cms_functions_camps` (`cms_functions_id`, `camps_id`) VALUES ('169', '100000000');
                INSERT INTO `cms_functions_camps` (`cms_functions_id`, `camps_id`) VALUES ('169', '100000001');
            ");

            //cms_usergroups_functions for Use Service
            $this->execute("
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('168', '1');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('168', '2');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('168', '4');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('168', '5');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('168', '6');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('168', '10');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('168', '11');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('168', '12');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('168', '13');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('168', '14');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('168', '15');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('168', '16');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('168', '17');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('168', '18');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('168', '20');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('168', '100000000');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('168', '100000001');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('168', '100000002');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('168', '100000198');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('168', '100000201');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('168', '100000204');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('168', '100000206');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('168', '100000207');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('168', '100000208');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('168', '100000210');
            ");

            //cms_usergroups_functions for Manage Services
            $this->execute("
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('169', '1');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('169', '2');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('169', '10');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('169', '11');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('169', '12');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('169', '15');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('169', '17');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('169', '100000001');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('169', '100000002');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('169', '100000206');
                INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('169', '100000207');
            "); 
        } else {
            $this->output->writeln("Only cms_functions is touched since we are in dropapp_production");
        }
    }

    public function down(): void
    {
        $dbName = $this->fetchRow('SELECT DATABASE();');
        $this->output->writeln("Running downwards migration on database: {$dbName[0]}");
        if ('dropapp_production' != $dbName[0]) {
            $this->execute("
                DELETE FROM `cms_usergroups_functions` WHERE `cms_functions_id` IN ('168', '169');
            ");

            $this->execute("
                DELETE FROM `cms_functions_camps` WHERE `cms_functions_id` IN ('168', '169');
            ");
        } else {
            $this->output->writeln("Only cms_functions is touched since we are in dropapp_production");
        }

        $this->execute("
            DELETE FROM `cms_functions` WHERE `id` IN ('168', '169');
        ");

        // make services subsection visible
        $this->execute("
            UPDATE `cms_functions` SET `visible` = '0' WHERE `id` = '131';
        ");
    }
}
