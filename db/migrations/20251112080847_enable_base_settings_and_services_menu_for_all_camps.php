<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class EnableBaseSettingsAndServicesMenuForAllCamps extends AbstractMigration
{
    public function up(): void
    {
        $dbName = $this->fetchRow('SELECT DATABASE();');
        $this->output->writeln("Running upwards migration on database: {$dbName[0]}");

        // Enable Base Settings menu (ID: 170) for few active camps
        $this->execute("
            INSERT INTO `cms_functions_camps`
            (`cms_functions_id`, `camps_id`)
            VALUES
            ('170', '3'),  -- IHA
            -- ('170', '20'), LHI
            ('170', '27'); -- TFS
        ");

        // Enable Use Service menu (ID: 168) for all active camps
        $this->execute("
            INSERT INTO `cms_functions_camps`
            (`cms_functions_id`, `camps_id`)
            VALUES
            -- ('168', '3'),
            ('168', '17'),
            -- ('168', '20'),
            ('168', '22'),
            ('168', '25'),
            -- ('168', '27'),
            ('168', '30'),
            ('168', '36'),
            ('168', '39'),
            ('168', '41'),
            ('168', '44'),
            ('168', '46'),
            ('168', '48'),
            ('168', '49'),
            ('168', '50'),
            ('168', '51'),
            ('168', '52'),
            ('168', '53'),
            ('168', '54');
        ");

        // Enable Manage Services menu (ID: 169) for all active camps
        $this->execute("
            INSERT INTO `cms_functions_camps`
            (`cms_functions_id`, `camps_id`)
            VALUES
            -- ('169', '3'),
            ('169', '17'),
            -- ('169', '20'),
            ('169', '22'),
            ('169', '25'),
            -- ('169', '27'),
            ('169', '30'),
            ('169', '36'),
            ('169', '39'),
            ('169', '41'),
            ('169', '44'),
            ('169', '46'),
            ('169', '48'),
            ('169', '49'),
            ('169', '50'),
            ('169', '51'),
            ('169', '52'),
            ('169', '53'),
            ('169', '54');
        ");

        // Base Settings menu (ID: 170) for all HoO usergroups of relevant camps already enabled

        // Enable Use Service menu (ID: 168) for all relevant usergroups
        $this->execute("
            INSERT INTO `cms_usergroups_functions`
            (`cms_functions_id`, `cms_usergroups_id`)
            VALUES
            -- Head of Operations
            ('168', '9'),
            -- ('168', '6'),
            ('168', '28'),
            -- ('168', '41'),
            ('168', '47'),
            ('168', '59'),
            -- ('168', '66'),
            ('168', '76'),
            ('168', '92'),
            ('168', '132'),
            ('168', '145'),
            ('168', '168'),
            ('168', '174'),
            -- Coordinator
            ('168', '29'),
            ('168', '40'),
            -- ('168', '42'),
            ('168', '48'),
            ('168', '60'),
            -- ('168', '67'),
            -- ('168', '125'),
            ('168', '77'),
            ('168', '93'),
            ('168', '98'),
            -- ('168', '7'),
            ('168', '133'),
            ('168', '146'),
            ('168', '158'),
            ('168', '163'),
            ('168', '169'),
            ('168', '175'),
            ('168', '181'),
            ('168', '187'),
            ('168', '193'),
            -- Volunteer / Freeshop Volunteer
            ('168', '2'),
            -- ('168', '43'),
            -- ('168', '202'),
            ('168', '49'),
            -- ('168', '68'),
            -- ('168', '124'),
            ('168', '78'),
            ('168', '94'),
            ('168', '99'),
            ('168', '101'),
            -- ('168', '8'),
            -- ('168', '200'),
            ('168', '134'),
            ('168', '136'),
            ('168', '147'),
            ('168', '149'),
            ('168', '159'),
            ('168', '161'),
            ('168', '164'),
            ('168', '166'),
            ('168', '170'),
            ('168', '172'),
            ('168', '176'),
            ('168', '178'),
            ('168', '182'),
            ('168', '184'),
            ('168', '188'),
            ('168', '190'),
            ('168', '194'),
            ('168', '196');

        ");

        // Enable Manage Services menu (ID: 169) for HoO and Coordinator usergroups
        $this->execute("
            INSERT INTO `cms_usergroups_functions`
            (`cms_functions_id`, `cms_usergroups_id`)
            VALUES
            -- Head of Operations
            ('169', '9'),
            -- ('169', '6'),
            ('169', '28'),
            -- ('169', '41'),
            ('169', '47'),
            ('169', '59'),
            -- ('169', '66'),
            ('169', '76'),
            ('169', '92'),
            ('169', '132'),
            ('169', '145'),
            ('169', '168'),
            ('169', '174'),
            -- Coordinator
            ('169', '29'),
            ('169', '40'),
            -- ('169', '42'),
            ('169', '48'),
            ('169', '60'),
            -- ('169', '67'),
            -- ('169', '125'),
            ('169', '77'),
            ('169', '93'),
            ('169', '98'),
            -- ('169', '7'),
            ('169', '133'),
            ('169', '146'),
            ('169', '158'),
            ('169', '163'),
            ('169', '169'),
            ('169', '175'),
            ('169', '181'),
            ('169', '187'),
            ('169', '193');
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
