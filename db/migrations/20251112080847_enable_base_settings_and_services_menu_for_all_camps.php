<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class EnableBaseSettingsAndServicesMenuForAllCamps extends AbstractMigration
{
    public function up(): void
    {
        $dbName = $this->fetchRow('SELECT DATABASE();');
        $this->output->writeln("Running upwards migration on database: {$dbName[0]}");

        // Enable Base Settings menu (ID: 170) for all active camps
        // Replace 99 with actual camp IDs
        $this->execute("
            INSERT INTO `cms_functions_camps` (`cms_functions_id`, `camps_id`) VALUES ('170', '99');
        ");

        // Enable Use Service menu (ID: 168) for all active camps
        // Replace 99 with actual camp IDs
        $this->execute("
            INSERT INTO `cms_functions_camps` (`cms_functions_id`, `camps_id`) VALUES ('168', '99');
        ");

        // Enable Manage Services menu (ID: 169) for all active camps
        // Replace 99 with actual camp IDs
        $this->execute("
            INSERT INTO `cms_functions_camps` (`cms_functions_id`, `camps_id`) VALUES ('169', '99');
        ");

        // Enable Base Settings menu (ID: 170) for all usergroups
        // Replace 77 with actual usergroup IDs
        $this->execute("
            INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('170', '77');
        ");

        // Enable Use Service menu (ID: 168) for all usergroups
        // Replace 77 with actual usergroup IDs
        $this->execute("
            INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('168', '77');
        ");

        // Enable Manage Services menu (ID: 169) for all usergroups
        // Replace 77 with actual usergroup IDs
        $this->execute("
            INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES ('169', '77');
        ");
    }

    public function down(): void
    {
        $dbName = $this->fetchRow('SELECT DATABASE();');
        $this->output->writeln("Running downwards migration on database: {$dbName[0]}");

        // Remove Base Settings menu (ID: 170) from camps
        // Replace 99 with actual camp IDs used in up()
        $this->execute("
            DELETE FROM `cms_functions_camps` WHERE `cms_functions_id` = '170' AND `camps_id` = '99';
        ");

        // Remove Use Service menu (ID: 168) from camps
        // Replace 99 with actual camp IDs used in up()
        $this->execute("
            DELETE FROM `cms_functions_camps` WHERE `cms_functions_id` = '168' AND `camps_id` = '99';
        ");

        // Remove Manage Services menu (ID: 169) from camps
        // Replace 99 with actual camp IDs used in up()
        $this->execute("
            DELETE FROM `cms_functions_camps` WHERE `cms_functions_id` = '169' AND `camps_id` = '99';
        ");

        // Remove Base Settings menu (ID: 170) from usergroups
        // Replace 77 with actual usergroup IDs used in up()
        $this->execute("
            DELETE FROM `cms_usergroups_functions` WHERE `cms_functions_id` = '170' AND `cms_usergroups_id` = '77';
        ");

        // Remove Use Service menu (ID: 168) from usergroups
        // Replace 77 with actual usergroup IDs used in up()
        $this->execute("
            DELETE FROM `cms_usergroups_functions` WHERE `cms_functions_id` = '168' AND `cms_usergroups_id` = '77';
        ");

        // Remove Manage Services menu (ID: 169) from usergroups
        // Replace 77 with actual usergroup IDs used in up()
        $this->execute("
            DELETE FROM `cms_usergroups_functions` WHERE `cms_functions_id` = '169' AND `cms_usergroups_id` = '77';
        ");
    }
}
