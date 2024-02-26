<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class AfterRotterdamSprint extends AbstractMigration
{
    public function change(): void
    {
        $this->table('camps', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8',
            'collation' => 'utf8_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->changeColumn('name', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'organisation_id',
            ])
            ->changeColumn('market', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'seq',
            ])
            ->changeColumn('familyidentifier', 'string', [
                'null' => false,
                'default' => 'Container',
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'market',
            ])
            ->changeColumn('schedulestart', 'string', [
                'null' => false,
                'default' => '11:00',
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'laundry',
            ])
            ->changeColumn('schedulestop', 'string', [
                'null' => false,
                'default' => '17:00',
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'schedulestart',
            ])
            ->changeColumn('scheduletimeslot', 'string', [
                'null' => false,
                'default' => '0.5',
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'schedulestop',
            ])
            ->changeColumn('schedulebreak', 'string', [
                'null' => false,
                'default' => '1',
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'scheduletimeslot',
            ])
            ->changeColumn('schedulebreakstart', 'string', [
                'null' => false,
                'default' => '13:00',
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'schedulebreak',
            ])
            ->changeColumn('schedulebreakduration', 'string', [
                'null' => false,
                'default' => '1',
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'schedulebreakstart',
            ])
            ->changeColumn('dropsperadult', 'string', [
                'null' => false,
                'default' => '100',
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'schedulebreakduration',
            ])
            ->changeColumn('dropsperchild', 'string', [
                'null' => false,
                'default' => '100',
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'dropsperadult',
            ])
            ->changeColumn('cyclestart', 'datetime', [
                'null' => true,
                'default' => '2019-01-01 00:00:00',
                'after' => 'dropsperchild',
            ])
            ->changeColumn('bicyclerenttime', 'integer', [
                'null' => false,
                'default' => '120',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'dropcapchild',
            ])
            ->changeColumn('bicycle_closingtime', 'string', [
                'null' => true,
                'default' => '17:30',
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'bicyclerenttime',
            ])
            ->changeColumn('bicycle_closingtime_saturday', 'string', [
                'null' => true,
                'default' => '16:30',
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'bicycle_closingtime',
            ])
            ->changeColumn('adult_age', 'integer', [
                'null' => true,
                'default' => '15',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'bicycle_closingtime_saturday',
            ])
            ->changeColumn('daystokeepdeletedpersons', 'integer', [
                'null' => true,
                'default' => '9999',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'adult_age',
            ])
            ->changeColumn('extraportion', 'integer', [
                'null' => true,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'daystokeepdeletedpersons',
            ])
            ->changeColumn('maxfooddrops_adult', 'integer', [
                'null' => true,
                'default' => '25',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'extraportion',
            ])
            ->changeColumn('maxfooddrops_child', 'integer', [
                'null' => true,
                'default' => '25',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'maxfooddrops_adult',
            ])
            ->changeColumn('laundry_cyclestart', 'date', [
                'null' => true,
                'default' => '2019-01-01',
                'after' => 'maxfooddrops_child',
            ])
            ->save()
        ;
        $this->table('cms_functions', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8',
            'collation' => 'utf8_bin',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->removeColumn('fororganisations')
            ->save()
        ;
        $this->table('cms_users', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8',
            'collation' => 'utf8_bin',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('valid_firstday', 'date', [
                'null' => true,
                'after' => 'cms_usergroups_id',
            ])
            ->addColumn('valid_lastday', 'date', [
                'null' => true,
                'after' => 'valid_firstday',
            ])
            ->save()
        ;
    }
}
