<?php

use Phinx\Migration\AbstractMigration;

class EnableMenuesForAllCamps extends AbstractMigration
{
    public function up(): void
    {
        $dbName = $this->fetchRow('SELECT DATABASE();');
        if ('dropapp_production' != $dbName) {
            return;
        }

        $this->execute(
            '
INSERT INTO cms_functions_camps
VALUES
-- Manage Beneficiaries
(118, 14),
(118, 17),
(118, 25),
(118, 28),
(118, 29),
(118, 31),
(118, 49),
-- Add Beneficiary
(158, 14),
(158, 17),
(158, 25),
(158, 28),
(158, 29),
(158, 31),
(158, 49),
-- Checkout
(87, 14),
(87, 17),
(87, 25),
(87, 28),
(87, 29),
(87, 31),
(87, 49),
-- Give Tokens to all
(92, 1),
(92, 2),
(92, 14),
(92, 15),
(92, 17),
(92, 18),
(92, 25),
(92, 28),
(92, 29),
(92, 31),
(92, 49),
-- Stockroom
(110, 14),
(110, 15),
(110, 16),
(110, 24),
(110, 25),
(110, 28),
(110, 29),
(110, 30),
(110, 31),
(110, 32),
(110, 35),
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
-- Camp 28
(118, 69),
(118, 70),
-- Camp 29
(118, 72),
(118, 73),
-- Camp 30
(118, 78),
-- Camp 31
(118, 79),
(118, 80),
-- Camp 49, entries already exist for usergroups 145, 163, 164, 166

-- // Add Beneficiary
-- Camp 17
(158, 28),
(158, 29),
(158, 40),
-- Camp 25
(158, 59),
(158, 60),
-- Camp 28
(158, 69),
(158, 70),
-- Camp 29
(158, 72),
(158, 73),
-- Camp 31
(158, 79),
(158, 80),

-- // Checkout
-- Camp 17
(87, 28),
(87, 29),
(87, 40),
-- Camp 25
(87, 59),
(87, 60),
-- Camp 28
(87, 69),
(87, 70),
-- Camp 29
(87, 72),
(87, 73),
-- Camp 30
(87, 78),
-- Camp 31
(87, 79),
(87, 80),

-- // Give tokens
-- Camp 1 (Test HoO)
(92, 9),
-- Camp 15
-- (92, 50) already exists
-- Camp 17
(92, 28),
(92, 29),
(92, 40),
-- Camp 25
(92, 59),
(92, 60),
-- Camp 28
(92, 69),
(92, 70),
-- Camp 29
(92, 72),
(92, 73),
-- Camp 30
(92, 77),
-- Camp 31
(92, 79),
(92, 80),

-- // Stockroom
-- Camp 3
(110, 8),
-- Camp 15
-- (110, 50), -- deleted UG
-- (110, 58), -- deleted UG
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
(110, 62),
-- Camp 27
(110, 75),
-- Camp 28
(110, 69),
(110, 70),
(110, 71),
-- Camp 29
(110, 72),
(110, 73),
(110, 74),
-- Camp 30
-- (110, 76) already exists
(110, 77),
(110, 78),
-- Camp 31
(110, 79),
(110, 80),
(110, 81),
-- Camp 32
(110, 82),
(110, 83),
-- (110, 84) already exists
-- Camp 36
(110, 92),
(110, 93),
-- (110, 94) already exists
;
'
        );
    }

    public function down() {
        $dbName = $this->fetchRow('SELECT DATABASE();');
        if ('dropapp_production' != $dbName) {
            return;
        }
    }
}
