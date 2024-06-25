<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class NewUserMgmt extends AbstractMigration
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
            ->addColumn('organisation_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'id',
            ])
            ->changeColumn('name', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'organisation_id',
            ])
            ->changeColumn('seq', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'name',
            ])
            ->addColumn('adult_age', 'integer', [
                'null' => true,
                'default' => '15',
                'limit' => '10',
                'signed' => false,
                'after' => 'bicyclerenttime',
            ])
            ->addColumn('daystokeepdeletedpersons', 'integer', [
                'null' => true,
                'default' => '9999',
                'limit' => '10',
                'signed' => false,
                'after' => 'adult_age',
            ])
            ->addColumn('extraportion', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'daystokeepdeletedpersons',
            ])
            ->addColumn('maxfooddrops_adult', 'integer', [
                'null' => true,
                'limit' => '10',
                'signed' => false,
                'after' => 'extraportion',
            ])
            ->addColumn('maxfooddrops_child', 'integer', [
                'null' => true,
                'limit' => '10',
                'signed' => false,
                'after' => 'maxfooddrops_adult',
            ])
            ->addColumn('laundry_cyclestart', 'date', [
                'null' => true,
                'after' => 'maxfooddrops_child',
            ])
            ->addColumn('bicycle_closingtime', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'laundry_cyclestart',
            ])
            ->addColumn('bicycle_closingtime_saturday', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'bicycle_closingtime',
            ])
            ->addColumn('created', 'datetime', [
                'null' => true,
                'after' => 'bicycle_closingtime_saturday',
            ])
            ->addColumn('created_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'created',
            ])
            ->addColumn('modified', 'datetime', [
                'null' => true,
                'after' => 'created_by',
            ])
            ->addColumn('modified_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'modified',
            ])
            ->removeColumn('require_qr')
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
            ->changeColumn('allusers', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'visible',
            ])
            ->addColumn('allcamps', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'allusers',
            ])
            ->addColumn('fororganisations', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'allcamps',
            ])
            ->save()
        ;
        $this->table('settings')
            ->rename('cms_settings')
            ->save()
        ;
        $this->table('cms_usergroups', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8',
            'collation' => 'utf8_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => 'enable',
            ])
            ->addColumn('label', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'id',
            ])
            ->addColumn('organisation_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'label',
            ])
            ->addColumn('created', 'datetime', [
                'null' => true,
                'after' => 'organisation_id',
            ])
            ->addColumn('created_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'created',
            ])
            ->addColumn('modified', 'datetime', [
                'null' => true,
                'after' => 'created_by',
            ])
            ->addColumn('modified_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'modified',
            ])
            ->addColumn('allow_laundry_startcycle', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'modified_by',
            ])
            ->addColumn('allow_laundry_block', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'allow_laundry_startcycle',
            ])
            ->addColumn('allow_borrow_adddelete', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'allow_laundry_block',
            ])
            ->create()
        ;
        $this->table('cms_usergroups_camps', [
            'id' => false,
            'engine' => 'InnoDB',
            'encoding' => 'utf8',
            'collation' => 'utf8_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('cms_usergroups_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
            ])
            ->addColumn('camp_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'cms_usergroups_id',
            ])
            ->create()
        ;
        $this->table('cms_usergroups_functions', [
            'id' => false,
            'engine' => 'InnoDB',
            'encoding' => 'utf8',
            'collation' => 'utf8_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('cms_usergroups_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
            ])
            ->addColumn('cms_functions_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'cms_usergroups_id',
            ])
            ->create()
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
            ->addColumn('cms_usergroups_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'deleted',
            ])
            ->removeColumn('coordinator')
            ->save()
        ;
        $this->table('laundry_stations', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8',
            'collation' => 'utf8_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->changeColumn('access', 'string', [
                'null' => false,
                'default' => 'TRUE',
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'label',
            ])
            ->save()
        ;
        $this->table('organisations', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8',
            'collation' => 'utf8_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => 'enable',
            ])
            ->addColumn('label', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'id',
            ])
            ->addColumn('created', 'datetime', [
                'null' => true,
                'after' => 'label',
            ])
            ->addColumn('created_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'created',
            ])
            ->addColumn('modified', 'datetime', [
                'null' => true,
                'after' => 'created_by',
            ])
            ->addColumn('modified_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'modified',
            ])
            ->create()
        ;
        $this->table('cms_access')->drop()->save();
        $this->table('cms_users_camps')->drop()->save();
        $this->table('food')->drop()->save();
        $this->table('food_distributions')->drop()->save();
        $this->table('food_transactions')->drop()->save();
        $this->table('pagetree')->drop()->save();
        $this->table('products_prices')->drop()->save();
        $this->table('redirect')->drop()->save();
        $this->table('settings_categories')->drop()->save();
    }
}
