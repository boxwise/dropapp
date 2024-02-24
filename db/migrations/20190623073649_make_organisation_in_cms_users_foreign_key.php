<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class MakeOrganisationInCmsUsersForeignKey extends AbstractMigration
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
            ->changeColumn('adult_age', 'integer', [
                'null' => true,
                'default' => '15',
                'limit' => MysqlAdapter::INT_REGULAR,
            ])
            ->changeColumn('bicycle', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'adult_age',
            ])
            ->changeColumn('bicycle_closingtime', 'string', [
                'null' => true,
                'default' => '17:30',
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'bicycle',
            ])
            ->changeColumn('bicycle_closingtime_saturday', 'string', [
                'null' => true,
                'default' => '16:30',
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'bicycle_closingtime',
            ])
            ->changeColumn('bicyclerenttime', 'integer', [
                'null' => false,
                'default' => '120',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'bicycle_closingtime_saturday',
            ])
            ->changeColumn('created', 'datetime', [
                'null' => true,
                'after' => 'bicyclerenttime',
            ])
            ->changeColumn('created_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'created',
            ])
            ->changeColumn('currencyname', 'string', [
                'null' => false,
                'default' => 'Tokens',
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'created_by',
            ])
            ->changeColumn('cyclestart', 'datetime', [
                'null' => true,
                'default' => '2019-01-01 00:00:00',
                'after' => 'currencyname',
            ])
            ->changeColumn('daystokeepdeletedpersons', 'integer', [
                'null' => true,
                'default' => '9999',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'cyclestart',
            ])
            ->changeColumn('delete_inactive_users', 'integer', [
                'null' => false,
                'default' => '30',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'daystokeepdeletedpersons',
            ])
            ->changeColumn('deleted', 'datetime', [
                'null' => true,
                'after' => 'delete_inactive_users',
            ])
            ->changeColumn('dropcapadult', 'integer', [
                'null' => false,
                'default' => '99999',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'deleted',
            ])
            ->changeColumn('dropcapchild', 'integer', [
                'null' => false,
                'default' => '99999',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'dropcapadult',
            ])
            ->changeColumn('dropsperadult', 'string', [
                'null' => false,
                'default' => '100',
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'dropcapchild',
            ])
            ->changeColumn('dropsperchild', 'string', [
                'null' => false,
                'default' => '100',
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'dropsperadult',
            ])
            ->changeColumn('extraportion', 'integer', [
                'null' => true,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'dropsperchild',
            ])
            ->changeColumn('familyidentifier', 'string', [
                'null' => false,
                'default' => 'Container',
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'extraportion',
            ])
            ->changeColumn('food', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'familyidentifier',
            ])
            ->changeColumn('idcard', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'id',
            ])
            ->changeColumn('laundry', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'idcard',
            ])
            ->changeColumn('laundry_cyclestart', 'date', [
                'null' => true,
                'default' => '2019-01-01',
                'after' => 'laundry',
            ])
            ->changeColumn('market', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'laundry_cyclestart',
            ])
            ->changeColumn('maxfooddrops_adult', 'integer', [
                'null' => true,
                'default' => '25',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'market',
            ])
            ->changeColumn('maxfooddrops_child', 'integer', [
                'null' => true,
                'default' => '25',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'maxfooddrops_adult',
            ])
            ->changeColumn('modified', 'datetime', [
                'null' => true,
                'after' => 'maxfooddrops_child',
            ])
            ->changeColumn('modified_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'modified',
            ])
            ->changeColumn('name', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'modified_by',
            ])
            ->changeColumn('organisation_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'name',
            ])
            ->changeColumn('schedulebreak', 'string', [
                'null' => false,
                'default' => '1',
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'organisation_id',
            ])
            ->changeColumn('schedulebreakduration', 'string', [
                'null' => false,
                'default' => '1',
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'schedulebreak',
            ])
            ->changeColumn('schedulebreakstart', 'string', [
                'null' => false,
                'default' => '13:00',
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'schedulebreakduration',
            ])
            ->changeColumn('schedulestart', 'string', [
                'null' => false,
                'default' => '11:00',
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'schedulebreakstart',
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
            ->changeColumn('seq', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'scheduletimeslot',
            ])
            ->changeColumn('workshop', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'seq',
            ])
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
            ->changeColumn('organisation_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'after' => 'naam',
            ])
            ->addForeignKey('organisation_id', 'organisations', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
        ;
    }
}
