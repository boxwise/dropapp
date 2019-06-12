<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class InitialSchema extends AbstractMigration
{
    public function change()
    {
        $this->table('borrow_categories', [
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
            ->create();
        $this->table('borrow_items', [
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
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'label',
            ])
            ->addColumn('category_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'deleted',
            ])
            ->addColumn('visible', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'category_id',
            ])
            ->addColumn('comment', 'text', [
                'null' => false,
                'limit' => 65535,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'visible',
            ])
            ->addColumn('created', 'datetime', [
                'null' => true,
                'after' => 'comment',
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
            ->addColumn('location_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'modified_by',
            ])
            ->create();
        $this->table('borrow_locations', [
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
            ->addColumn('camp_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'id',
            ])
            ->addColumn('location', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'camp_id',
            ])
            ->create();
        $this->table('borrow_transactions', [
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
            ->addColumn('transaction_date', 'datetime', [
                'null' => true,
                'after' => 'id',
            ])
            ->addColumn('bicycle_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'transaction_date',
            ])
            ->addColumn('people_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'bicycle_id',
            ])
            ->addColumn('status', 'string', [
                'null' => true,
                'limit' => 5,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'people_id',
            ])
            ->addColumn('created', 'datetime', [
                'null' => true,
                'after' => 'status',
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
            ->addColumn('lights', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'modified_by',
            ])
            ->addColumn('helmet', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'lights',
            ])
            ->addColumn('location_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'helmet',
            ])
            ->create();
        $this->table('camps', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'latin1',
                'collation' => 'latin1_swedish_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => 'enable',
            ])
            ->addColumn('name', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'id',
            ])
            ->addColumn('seq', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'name',
            ])
            ->addColumn('require_qr', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'seq',
            ])
            ->addColumn('market', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'require_qr',
            ])
            ->addColumn('familyidentifier', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'market',
            ])
            ->addColumn('delete_inactive_users', 'integer', [
                'null' => false,
                'default' => '30',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'familyidentifier',
            ])
            ->addColumn('food', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'delete_inactive_users',
            ])
            ->addColumn('bicycle', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'food',
            ])
            ->addColumn('idcard', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'bicycle',
            ])
            ->addColumn('workshop', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'idcard',
            ])
            ->addColumn('laundry', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'workshop',
            ])
            ->addColumn('schedulestart', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'laundry',
            ])
            ->addColumn('schedulestop', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'schedulestart',
            ])
            ->addColumn('schedulebreak', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'schedulestop',
            ])
            ->addColumn('schedulebreakstart', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'schedulebreak',
            ])
            ->addColumn('schedulebreakduration', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'schedulebreakstart',
            ])
            ->addColumn('scheduletimeslot', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'schedulebreakduration',
            ])
            ->addColumn('dropsperadult', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'scheduletimeslot',
            ])
            ->addColumn('dropsperchild', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'dropsperadult',
            ])
            ->addColumn('cyclestart', 'datetime', [
                'null' => true,
                'after' => 'dropsperchild',
            ])
            ->addColumn('dropcapadult', 'integer', [
                'null' => false,
                'default' => '99999',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'cyclestart',
            ])
            ->addColumn('dropcapchild', 'integer', [
                'null' => false,
                'default' => '99999',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'dropcapadult',
            ])
            ->addColumn('bicyclerenttime', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'dropcapchild',
            ])
            ->create();
        $this->table('cms_access', [
                'id' => false,
                'engine' => 'InnoDB',
                'encoding' => 'latin1',
                'collation' => 'latin1_swedish_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('cms_users_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
            ])
            ->addColumn('cms_functions_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'cms_users_id',
            ])
            ->create();
        $this->table('cms_functions', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'MyISAM',
                'encoding' => 'utf8',
                'collation' => 'utf8_bin',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => 'enable',
            ])
            ->addColumn('parent_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'id',
            ])
            ->addColumn('title_en', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'parent_id',
            ])
            ->addColumn('include', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'title_en',
            ])
            ->addColumn('seq', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'include',
            ])
            ->addColumn('created', 'datetime', [
                'null' => true,
                'after' => 'seq',
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
            ->addColumn('alert', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'modified_by',
            ])
            ->addColumn('adminonly', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'alert',
            ])
            ->addColumn('visible', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'adminonly',
            ])
            ->addColumn('allusers', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'visible',
            ])
            ->create();
        $this->table('cms_functions_camps', [
                'id' => false,
                'engine' => 'InnoDB',
                'encoding' => 'utf8',
                'collation' => 'utf8_general_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('cms_functions_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
            ])
            ->addColumn('camps_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'cms_functions_id',
            ])
            ->create();
        $this->table('cms_users', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'MyISAM',
                'encoding' => 'utf8',
                'collation' => 'utf8_bin',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => 'enable',
            ])
            ->addColumn('pass', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 40,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'id',
            ])
            ->addColumn('naam', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'pass',
            ])
            ->addColumn('email', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'naam',
            ])
            ->addColumn('is_admin', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'email',
            ])
            ->addColumn('lastlogin', 'datetime', [
                'null' => false,
                'after' => 'is_admin',
            ])
            ->addColumn('lastaction', 'datetime', [
                'null' => false,
                'after' => 'lastlogin',
            ])
            ->addColumn('created', 'datetime', [
                'null' => true,
                'after' => 'lastaction',
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
            ->addColumn('resetpassword', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8_bin',
                'encoding' => 'utf8',
                'after' => 'modified_by',
            ])
            ->addColumn('language', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'resetpassword',
            ])
            ->addColumn('deleted', 'datetime', [
                'null' => false,
                'after' => 'language',
            ])
            ->addColumn('coordinator', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'deleted',
            ])
            ->create();
        $this->table('cms_users_camps', [
                'id' => false,
                'engine' => 'InnoDB',
                'encoding' => 'utf8',
                'collation' => 'utf8_general_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('cms_users_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
            ])
            ->addColumn('camps_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'cms_users_id',
            ])
            ->create();
        $this->table('food', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'latin1',
                'collation' => 'latin1_swedish_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => 'enable',
            ])
            ->addColumn('name', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'id',
            ])
            ->addColumn('package', 'float', [
                'null' => false,
                'default' => '0',
                'after' => 'name',
            ])
            ->addColumn('unit_id', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'package',
            ])
            ->addColumn('peradult', 'float', [
                'null' => false,
                'default' => '0',
                'after' => 'unit_id',
            ])
            ->addColumn('perchild', 'float', [
                'null' => false,
                'default' => '0',
                'after' => 'peradult',
            ])
            ->addColumn('stock', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'perchild',
            ])
            ->addColumn('price', 'float', [
                'null' => false,
                'default' => '0',
                'after' => 'stock',
            ])
            ->addColumn('created', 'datetime', [
                'null' => true,
                'after' => 'price',
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
            ->addColumn('visible', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'modified_by',
            ])
            ->addColumn('deleted', 'datetime', [
                'null' => false,
                'after' => 'visible',
            ])
            ->create();
        $this->table('food_distributions', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'MyISAM',
                'encoding' => 'latin1',
                'collation' => 'latin1_swedish_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => 'enable',
            ])
            ->addColumn('label', 'string', [
                'null' => false,
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'id',
            ])
            ->addColumn('food_1', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'label',
            ])
            ->addColumn('food_2', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'food_1',
            ])
            ->addColumn('food_3', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'food_2',
            ])
            ->addColumn('food_4', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'food_3',
            ])
            ->addColumn('food_5', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'food_4',
            ])
            ->addColumn('food_6', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'food_5',
            ])
            ->addColumn('deleted', 'datetime', [
                'null' => true,
                'after' => 'food_6',
            ])
            ->addColumn('visible', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'deleted',
            ])
            ->addColumn('created', 'datetime', [
                'null' => true,
                'after' => 'visible',
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
            ->create();
        $this->table('food_transactions', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'MyISAM',
                'encoding' => 'latin1',
                'collation' => 'latin1_swedish_ci',
                'comment' => '',
                'row_format' => 'FIXED',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => 'enable',
            ])
            ->addColumn('people_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'id',
            ])
            ->addColumn('dist_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'people_id',
            ])
            ->addColumn('food_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'dist_id',
            ])
            ->addColumn('count', 'float', [
                'null' => false,
                'after' => 'food_id',
            ])
            ->addColumn('created', 'datetime', [
                'null' => false,
                'after' => 'count',
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
            ->create();
        $this->table('genders', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'latin1',
                'collation' => 'latin1_swedish_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => 'enable',
            ])
            ->addColumn('label', 'string', [
                'null' => false,
                'limit' => 20,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'id',
            ])
            ->addColumn('shortlabel', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'label',
            ])
            ->addColumn('seq', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'shortlabel',
            ])
            ->addColumn('created', 'datetime', [
                'null' => true,
                'after' => 'seq',
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
            ->addColumn('male', 'boolean', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'modified_by',
            ])
            ->addColumn('female', 'boolean', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'male',
            ])
            ->addColumn('adult', 'boolean', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'female',
            ])
            ->addColumn('child', 'boolean', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'adult',
            ])
            ->addColumn('baby', 'boolean', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'child',
            ])
            ->addColumn('color', 'string', [
                'null' => false,
                'limit' => 20,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'baby',
            ])
            ->create();
        $this->table('history', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'latin1',
                'collation' => 'latin1_swedish_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => 'enable',
            ])
            ->addColumn('tablename', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'id',
            ])
            ->addColumn('record_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'tablename',
            ])
            ->addColumn('changes', 'text', [
                'null' => true,
                'limit' => 65535,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'record_id',
            ])
            ->addColumn('user_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'changes',
            ])
            ->addColumn('ip', 'string', [
                'null' => true,
                'limit' => 20,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'user_id',
            ])
            ->addColumn('changedate', 'datetime', [
                'null' => true,
                'after' => 'ip',
            ])
            ->addColumn('from_int', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'changedate',
            ])
            ->addColumn('to_int', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'from_int',
            ])
            ->addColumn('from_float', 'float', [
                'null' => true,
                'after' => 'to_int',
            ])
            ->addColumn('to_float', 'float', [
                'null' => true,
                'after' => 'from_float',
            ])
            ->create();
        $this->table('itemsout', [
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
            ->addColumn('product_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'id',
            ])
            ->addColumn('size_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'product_id',
            ])
            ->addColumn('count', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'size_id',
            ])
            ->addColumn('movedate', 'datetime', [
                'null' => true,
                'after' => 'count',
            ])
            ->addColumn('from_location', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'movedate',
            ])
            ->addColumn('to_location', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'from_location',
            ])
            ->create();
        $this->table('languages', [
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
            ->addColumn('visible', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'id',
            ])
            ->addColumn('code', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'visible',
            ])
            ->addColumn('locale', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'code',
            ])
            ->addColumn('name', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'locale',
            ])
            ->addColumn('strftime_dateformat', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'name',
            ])
            ->addColumn('smarty_dateformat', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'strftime_dateformat',
            ])
            ->addColumn('seq', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'smarty_dateformat',
            ])
            ->addColumn('rtl', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'seq',
            ])
            ->create();
        $this->table('laundry_appointments', [
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
            ->addColumn('cyclestart', 'date', [
                'null' => true,
                'after' => 'id',
            ])
            ->addColumn('timeslot', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'cyclestart',
            ])
            ->addColumn('people_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'timeslot',
            ])
            ->addColumn('created', 'datetime', [
                'null' => true,
                'after' => 'people_id',
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
            ->addColumn('noshow', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'modified_by',
            ])
            ->addColumn('dropoff', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'noshow',
            ])
            ->addColumn('collected', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'dropoff',
            ])
            ->addColumn('comment', 'text', [
                'null' => false,
                'limit' => 65535,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'collected',
            ])
            ->create();
        $this->table('laundry_machines', [
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
            ->create();
        $this->table('laundry_slots', [
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
            ->addColumn('day', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'id',
            ])
            ->addColumn('time', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'day',
            ])
            ->addColumn('machine', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'time',
            ])
            ->create();
        $this->table('laundry_stations', [
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
                'limit' => 50,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'id',
            ])
            ->addColumn('access', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'label',
            ])
            ->create();
        $this->table('laundry_times', [
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
                'limit' => 20,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'id',
            ])
            ->create();
        $this->table('library', [
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
            ->addColumn('code', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'id',
            ])
            ->addColumn('booktitle_en', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'code',
            ])
            ->addColumn('booktitle_ar', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'booktitle_en',
            ])
            ->addColumn('author', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'booktitle_ar',
            ])
            ->addColumn('type_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'author',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'type_id',
            ])
            ->addColumn('visible', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'deleted',
            ])
            ->addColumn('comment', 'text', [
                'null' => false,
                'limit' => 65535,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'visible',
            ])
            ->addColumn('created', 'datetime', [
                'null' => true,
                'after' => 'comment',
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
            ->addColumn('camp_id', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'modified_by',
            ])
            ->create();
        $this->table('library_transactions', [
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
            ->addColumn('transaction_date', 'datetime', [
                'null' => true,
                'after' => 'id',
            ])
            ->addColumn('book_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'transaction_date',
            ])
            ->addColumn('people_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'book_id',
            ])
            ->addColumn('status', 'string', [
                'null' => true,
                'limit' => 5,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'people_id',
            ])
            ->addColumn('created', 'datetime', [
                'null' => true,
                'after' => 'status',
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
            ->addColumn('comment', 'text', [
                'null' => false,
                'limit' => 65535,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'modified_by',
            ])
            ->create();
        $this->table('library_type', [
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
            ->addColumn('camp_id', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'label',
            ])
            ->create();
        $this->table('locations', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'latin1',
                'collation' => 'latin1_swedish_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => 'enable',
            ])
            ->addColumn('label', 'string', [
                'null' => false,
                'limit' => 20,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'id',
            ])
            ->addColumn('camp_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'label',
            ])
            ->addColumn('seq', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'camp_id',
            ])
            ->addColumn('created', 'datetime', [
                'null' => true,
                'after' => 'seq',
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
            ->addColumn('visible', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'modified_by',
            ])
            ->addColumn('container_stock', 'integer', [
                'null' => true,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'visible',
            ])
            ->addColumn('is_market', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'container_stock',
            ])
            ->addColumn('is_donated', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'is_market',
            ])
            ->addColumn('is_lost', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'is_donated',
            ])
            ->create();
        $this->table('log', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'latin1',
                'collation' => 'latin1_swedish_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => 'enable',
            ])
            ->addColumn('logdate', 'datetime', [
                'null' => true,
                'after' => 'id',
            ])
            ->addColumn('content', 'text', [
                'null' => true,
                'limit' => 65535,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'logdate',
            ])
            ->addColumn('ip', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'content',
            ])
            ->addColumn('user', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'ip',
            ])
            ->create();
        $this->table('need_periods', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'MyISAM',
                'encoding' => 'latin1',
                'collation' => 'latin1_swedish_ci',
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
                'null' => false,
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'id',
            ])
            ->addColumn('week_min', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'label',
            ])
            ->addColumn('week_max', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'week_min',
            ])
            ->create();
        $this->table('numbers', [
                'id' => false,
                'primary_key' => ['value'],
                'engine' => 'InnoDB',
                'encoding' => 'latin1',
                'collation' => 'latin1_swedish_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('value', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => 'enable',
            ])
            ->addColumn('label', 'string', [
                'null' => false,
                'limit' => 20,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'value',
            ])
            ->create();
        $this->table('pagetree', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'MyISAM',
                'encoding' => 'utf8',
                'collation' => 'utf8_general_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => 'enable',
            ])
            ->addColumn('parent_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'id',
            ])
            ->addColumn('seq', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'parent_id',
            ])
            ->addColumn('owner_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'seq',
            ])
            ->addColumn('visible', 'integer', [
                'null' => true,
                'default' => '1',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'owner_id',
            ])
            ->addColumn('sharing', 'integer', [
                'null' => true,
                'default' => '1',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'visible',
            ])
            ->addColumn('searchable', 'integer', [
                'null' => true,
                'default' => '1',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'sharing',
            ])
            ->addColumn('relatable', 'integer', [
                'null' => true,
                'default' => '1',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'searchable',
            ])
            ->addColumn('footer', 'integer', [
                'null' => true,
                'default' => '1',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'relatable',
            ])
            ->addColumn('subtemplate', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'footer',
            ])
            ->addColumn('image', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'subtemplate',
            ])
            ->addColumn('og_image', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'image',
            ])
            ->addColumn('menutitle', 'string', [
                'null' => true,
                'default' => '',
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'og_image',
            ])
            ->addColumn('pagesubtitle', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'menutitle',
            ])
            ->addColumn('pagetitle', 'string', [
                'null' => true,
                'default' => '',
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'pagesubtitle',
            ])
            ->addColumn('windowtitle', 'string', [
                'null' => true,
                'default' => '',
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'pagetitle',
            ])
            ->addColumn('url', 'string', [
                'null' => true,
                'default' => '',
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'windowtitle',
            ])
            ->addColumn('content', 'text', [
                'null' => true,
                'limit' => 65535,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'url',
            ])
            ->addColumn('intro', 'text', [
                'null' => true,
                'limit' => 65535,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'content',
            ])
            ->addColumn('description', 'text', [
                'null' => true,
                'limit' => 65535,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'intro',
            ])
            ->addColumn('startdate', 'datetime', [
                'null' => true,
                'after' => 'description',
            ])
            ->addColumn('created', 'datetime', [
                'null' => true,
                'after' => 'startdate',
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
            ->addColumn('adwords', 'text', [
                'null' => true,
                'limit' => 65535,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'modified_by',
            ])
            ->addColumn('deleted', 'datetime', [
                'null' => false,
                'after' => 'adwords',
            ])
            ->create();
        $this->table('people', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'latin1',
                'collation' => 'latin1_swedish_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => 'enable',
            ])
            ->addColumn('parent_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'id',
            ])
            ->addColumn('seq', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'parent_id',
            ])
            ->addColumn('firstname', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'seq',
            ])
            ->addColumn('lastname', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'firstname',
            ])
            ->addColumn('date_of_birth', 'date', [
                'null' => true,
                'after' => 'lastname',
            ])
            ->addColumn('gender', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'date_of_birth',
            ])
            ->addColumn('family_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'gender',
            ])
            ->addColumn('visible', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'family_id',
            ])
            ->addColumn('created', 'datetime', [
                'null' => true,
                'after' => 'visible',
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
            ->addColumn('url', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'modified_by',
            ])
            ->addColumn('container', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'url',
            ])
            ->addColumn('tent', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'container',
            ])
            ->addColumn('email', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'tent',
            ])
            ->addColumn('pass', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'email',
            ])
            ->addColumn('language', 'integer', [
                'null' => false,
                'default' => '5',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'pass',
            ])
            ->addColumn('resetpassword', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'language',
            ])
            ->addColumn('comments', 'text', [
                'null' => true,
                'limit' => 65535,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'resetpassword',
            ])
            ->addColumn('workshoptraining', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'comments',
            ])
            ->addColumn('workshopsupervisor', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'workshoptraining',
            ])
            ->addColumn('phone', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'workshopsupervisor',
            ])
            ->addColumn('bicycletraining', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'phone',
            ])
            ->addColumn('camp_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'bicycletraining',
            ])
            ->addColumn('deleted', 'datetime', [
                'null' => false,
                'after' => 'camp_id',
            ])
            ->addColumn('extraportion', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'deleted',
            ])
            ->addColumn('notregistered', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'extraportion',
            ])
            ->addColumn('workshopban', 'date', [
                'null' => true,
                'after' => 'notregistered',
            ])
            ->addColumn('bicycleban', 'date', [
                'null' => true,
                'after' => 'workshopban',
            ])
            ->addColumn('bicyclebancomment', 'text', [
                'null' => false,
                'limit' => 65535,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'bicycleban',
            ])
            ->addColumn('workshopbancomment', 'text', [
                'null' => false,
                'limit' => 65535,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'bicyclebancomment',
            ])
            ->addColumn('volunteer', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'workshopbancomment',
            ])
            ->addColumn('laundryblock', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'volunteer',
            ])
            ->addColumn('laundrycomment', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'laundryblock',
            ])
            ->addColumn('approvalsigned', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'laundrycomment',
            ])
            ->addColumn('signaturefield', 'text', [
                'null' => true,
                'limit' => 65535,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'approvalsigned',
            ])
        ->addIndex(['parent_id'], [
                'name' => 'parent_id',
                'unique' => false,
            ])
        ->addIndex(['container'], [
                'name' => 'container',
                'unique' => false,
            ])
        ->addIndex(['camp_id'], [
                'name' => 'camp_id',
                'unique' => false,
            ])
            ->create();
        $this->table('product_categories', [
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
                'limit' => 50,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'id',
            ])
            ->addColumn('seq', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'label',
            ])
            ->create();
        $this->table('products', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'latin1',
                'collation' => 'latin1_swedish_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => 'enable',
            ])
            ->addColumn('name', 'string', [
                'null' => false,
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'id',
            ])
            ->addColumn('groupname', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'name',
            ])
            ->addColumn('category_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'groupname',
            ])
            ->addColumn('gender_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'category_id',
            ])
            ->addColumn('sizegroup_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'gender_id',
            ])
            ->addColumn('camp_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'sizegroup_id',
            ])
            ->addColumn('value', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'camp_id',
            ])
            ->addColumn('amountneeded', 'float', [
                'null' => false,
                'default' => '1',
                'after' => 'value',
            ])
            ->addColumn('created', 'datetime', [
                'null' => true,
                'after' => 'amountneeded',
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
            ->addColumn('maxperadult', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'modified_by',
            ])
            ->addColumn('maxperchild', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'maxperadult',
            ])
            ->addColumn('stockincontainer', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'maxperchild',
            ])
            ->addColumn('comments', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'stockincontainer',
            ])
            ->addColumn('deleted', 'datetime', [
                'null' => false,
                'after' => 'comments',
            ])
            ->create();
        $this->table('products_prices', [
                'id' => false,
                'engine' => 'InnoDB',
                'encoding' => 'latin1',
                'collation' => 'latin1_swedish_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('product_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
            ])
            ->addColumn('camp_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'product_id',
            ])
            ->addColumn('price', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'camp_id',
            ])
            ->create();
        $this->table('qr', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'latin1',
                'collation' => 'latin1_swedish_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => 'enable',
            ])
            ->addColumn('code', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'id',
            ])
            ->addColumn('created', 'datetime', [
                'null' => true,
                'after' => 'code',
            ])
            ->create();
        $this->table('redirect', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'latin1',
                'collation' => 'latin1_swedish_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => 'enable',
            ])
            ->addColumn('source', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'id',
            ])
            ->addColumn('destination', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'source',
            ])
            ->addColumn('created', 'datetime', [
                'null' => true,
                'after' => 'destination',
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
            ->create();
        $this->table('settings', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'MyISAM',
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
            ->addColumn('category_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'after' => 'id',
            ])
            ->addColumn('type', 'string', [
                'null' => true,
                'limit' => 20,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'category_id',
            ])
            ->addColumn('code', 'string', [
                'null' => false,
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'type',
            ])
            ->addColumn('description', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'code',
            ])
            ->addColumn('options', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'description',
            ])
            ->addColumn('value', 'text', [
                'null' => true,
                'limit' => 65535,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'options',
            ])
            ->addColumn('hidden', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'value',
            ])
            ->addColumn('created', 'datetime', [
                'null' => true,
                'after' => 'hidden',
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
        ->addIndex(['code'], [
                'name' => 'code',
                'unique' => true,
            ])
            ->create();
        $this->table('settings_categories', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'latin1',
                'collation' => 'latin1_swedish_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => 'enable',
            ])
            ->addColumn('seq', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'after' => 'id',
            ])
            ->addColumn('name', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'seq',
            ])
            ->addColumn('admin_only', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'name',
            ])
            ->create();
        $this->table('sizegroup', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'latin1',
                'collation' => 'latin1_swedish_ci',
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
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'id',
            ])
            ->addColumn('seq', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'label',
            ])
            ->create();
        $this->table('sizes', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'latin1',
                'collation' => 'latin1_swedish_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => 'enable',
            ])
            ->addColumn('label', 'string', [
                'null' => false,
                'limit' => 20,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'id',
            ])
            ->addColumn('sizegroup_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'label',
            ])
            ->addColumn('portion', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'sizegroup_id',
            ])
            ->addColumn('seq', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'portion',
            ])
            ->addColumn('created', 'datetime', [
                'null' => true,
                'after' => 'seq',
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
            ->create();
        $this->table('stock', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'latin1',
                'collation' => 'latin1_swedish_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => 'enable',
            ])
            ->addColumn('box_id', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 11,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'id',
            ])
            ->addColumn('product_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'box_id',
            ])
            ->addColumn('size_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'product_id',
            ])
            ->addColumn('items', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'size_id',
            ])
            ->addColumn('location_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'items',
            ])
            ->addColumn('qr_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'location_id',
            ])
            ->addColumn('comments', 'text', [
                'null' => false,
                'limit' => 65535,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'qr_id',
            ])
            ->addColumn('_type', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'comments',
            ])
            ->addColumn('_gender', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => '_type',
            ])
            ->addColumn('_size', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => '_gender',
            ])
            ->addColumn('created', 'datetime', [
                'null' => true,
                'after' => '_size',
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
            ->addColumn('ordered', 'datetime', [
                'null' => true,
                'after' => 'modified_by',
            ])
            ->addColumn('ordered_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'ordered',
            ])
            ->addColumn('picked', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'ordered_by',
            ])
            ->addColumn('picked_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'picked',
            ])
            ->addColumn('deleted', 'datetime', [
                'null' => false,
                'after' => 'picked_by',
                'default' => '0000-00-00 00:00:00',
            ])
        ->addIndex(['box_id'], [
                'name' => 'box_id',
                'unique' => false,
            ])
        ->addIndex(['location_id'], [
                'name' => 'location_id',
                'unique' => false,
            ])
        ->addIndex(['product_id'], [
                'name' => 'product_id',
                'unique' => false,
            ])
        ->addIndex(['size_id'], [
                'name' => 'size_id',
                'unique' => false,
            ])
            ->create();
        $this->table('tipofday', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'latin1',
                'collation' => 'latin1_swedish_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => 'enable',
            ])
            ->addColumn('title', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'id',
            ])
            ->addColumn('content', 'text', [
                'null' => true,
                'limit' => 65535,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'title',
            ])
            ->create();
        $this->table('transactions', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'latin1',
                'collation' => 'latin1_swedish_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => 'enable',
            ])
            ->addColumn('people_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'id',
            ])
            ->addColumn('product_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'people_id',
            ])
            ->addColumn('size_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'product_id',
            ])
            ->addColumn('count', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'size_id',
            ])
            ->addColumn('description', 'string', [
                'null' => false,
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'count',
            ])
            ->addColumn('drops', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'description',
            ])
            ->addColumn('transaction_date', 'datetime', [
                'null' => false,
                'after' => 'drops',
            ])
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'transaction_date',
            ])
            ->addColumn('created', 'datetime', [
                'null' => true,
                'after' => 'user_id',
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
        ->addIndex(['people_id'], [
                'name' => 'people_id',
                'unique' => false,
            ])
        ->addIndex(['transaction_date'], [
                'name' => 'transaction_date',
                'unique' => false,
            ])
            ->create();
        $this->table('translate', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'MyISAM',
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
            ->addColumn('category_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'id',
            ])
            ->addColumn('type', 'string', [
                'null' => false,
                'limit' => 20,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'category_id',
            ])
            ->addColumn('code', 'string', [
                'null' => true,
                'default' => '',
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'type',
            ])
            ->addColumn('description', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'code',
            ])
            ->addColumn('nl', 'text', [
                'null' => false,
                'limit' => 65535,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'description',
            ])
            ->addColumn('en', 'text', [
                'null' => false,
                'limit' => 65535,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'nl',
            ])
            ->addColumn('fr', 'text', [
                'null' => false,
                'limit' => 65535,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'en',
            ])
            ->addColumn('hidden', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'fr',
            ])
            ->addColumn('created', 'datetime', [
                'null' => true,
                'after' => 'hidden',
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
            ->addColumn('deleted', 'datetime', [
                'null' => false,
                'after' => 'modified_by',
            ])
            ->create();
        $this->table('translate_categories', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'latin1',
                'collation' => 'latin1_swedish_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => 'enable',
            ])
            ->addColumn('name', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'id',
            ])
            ->create();
        $this->table('units', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'latin1',
                'collation' => 'latin1_swedish_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => 'enable',
            ])
            ->addColumn('label', 'string', [
                'null' => false,
                'limit' => 20,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'id',
            ])
            ->addColumn('longlabel', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'label',
            ])
            ->addColumn('seq', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'longlabel',
            ])
            ->addColumn('created', 'datetime', [
                'null' => true,
                'after' => 'seq',
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
            ->create();
        $this->table('x_people_languages', [
                'id' => false,
                'engine' => 'InnoDB',
                'encoding' => 'utf8',
                'collation' => 'utf8_general_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('people_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
            ])
            ->addColumn('language_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'people_id',
            ])
            ->create();
    }
}
