<?php

use Phinx\Migration\AbstractMigration;

class EnableMenuesForAllCamps extends AbstractMigration
{
    public function up(): void
    {
        $dbName = $this->fetchRow('SELECT DATABASE();');
        if ('dropapp_production' != $dbName[0]) {
            return;
        }

        $this->execute(
            '
INSERT INTO cms_functions_camps
VALUES
-- Manage Beneficiaries
(118, 17),
(118, 25),
(118, 49),
-- Add Beneficiary
(158, 17),
(158, 25),
(158, 49),
-- Checkout
(87, 17),
(87, 25),
(87, 49),
-- Give Tokens to all
(92, 1),
(92, 17),
(92, 25),
(92, 49),
-- Stockroom
(110, 25),
(110, 30),
(110, 36),
(110, 49);
'
        );

        $this->execute(
            '
INSERT INTO cms_usergroups_functions
VALUES
-- // Manage Beneficiaries
-- Camp 17
(118, 28),
(118, 29),
(118, 40),
-- Camp 25
(118, 59),
(118, 60),
-- Camp 30
(118, 78),
-- Camp 49, entries already exist for usergroups 145, 163, 164, 166

-- // Add Beneficiary
-- Camp 17
(158, 28),
(158, 29),
(158, 40),
-- Camp 25
(158, 59),
(158, 60),

-- // Checkout
-- Camp 17
(87, 28),
(87, 29),
(87, 40),
-- Camp 25
(87, 59),
(87, 60),
-- Camp 30
(87, 78),

-- // Give tokens
-- Camp 1 (Test HoO)
(92, 9),
-- Camp 17
(92, 28),
(92, 29),
(92, 40),
-- Camp 25
(92, 59),
(92, 60),
-- Camp 30
(92, 77),

-- // Stockroom
-- Camp 3
(110, 8),
-- Camp 17
-- (110, 28) already exists
(110, 29),
(110, 30),
(110, 36),
(110, 40),
-- Camp 22
(110, 47),
(110, 48),
(110, 49),
-- Camp 25
(110, 59),
(110, 60),
(110, 61),
-- Camp 27
(110, 75),
-- Camp 30
-- (110, 76) already exists
(110, 77),
(110, 78),
-- Camp 36
(110, 92),
(110, 93)
-- (110, 94) already exists
;
'
        );
    }

    public function down()
    {
        $dbName = $this->fetchRow('SELECT DATABASE();');
        if ('dropapp_production' != $dbName[0]) {
            return;
        }

        $this->execute(
            '
DELETE FROM cms_functions_camps
WHERE (cms_functions_id, camps_id) in (
-- Manage Beneficiaries
(118, 17),
(118, 25),
(118, 49),
-- Add Beneficiary
(158, 17),
(158, 25),
(158, 49),
-- Checkout
(87, 17),
(87, 25),
(87, 49),
-- Give Tokens to all
(92, 1),
(92, 17),
(92, 25),
(92, 49),
-- Stockroom
(110, 25),
(110, 30),
(110, 36),
(110, 49)
);
'
        );

        $this->execute(
            '
DELETE FROM cms_usergroups_functions
WHERE (cms_functions_id, cms_usergroups_id) in (
-- // Manage Beneficiaries
(118, 28),
(118, 29),
(118, 40),
(118, 59),
(118, 60),
(118, 78),

-- // Add Beneficiary
(158, 28),
(158, 29),
(158, 40),
(158, 59),
(158, 60),

-- // Checkout
(87, 28),
(87, 29),
(87, 40),
(87, 59),
(87, 60),
(87, 78),

-- // Give tokens
(92, 9),
(92, 28),
(92, 29),
(92, 40),
(92, 59),
(92, 60),
(92, 77),

-- // Stockroom
(110, 8),
(110, 29),
(110, 30),
(110, 36),
(110, 40),
(110, 47),
(110, 48),
(110, 49),
(110, 59),
(110, 60),
(110, 61),
(110, 75),
(110, 77),
(110, 78),
(110, 92),
(110, 93)
);
'
        );
    }
}
