<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class AddResettokenColumnInBases extends AbstractMigration
{
    public function change()
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
            ->addColumn('resettokens', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'food',
            ])
            ->save();
        $this->table('cms_usergroups', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8',
                'collation' => 'utf8_general_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
        ->changeColumn('created', 'datetime', [
                'null' => true,
                'after' => 'label',
            ])
        ->changeColumn('created_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'created',
            ])
        ->changeColumn('modified', 'datetime', [
                'null' => true,
                'after' => 'created_by',
            ])
        ->changeColumn('modified_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'modified',
            ])
        ->changeColumn('organisation_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'after' => 'modified_by',
            ])
        ->changeColumn('userlevel', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'after' => 'organisation_id',
            ])
        ->changeColumn('allow_laundry_startcycle', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'userlevel',
            ])
        ->changeColumn('allow_laundry_block', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'allow_laundry_startcycle',
            ])
        ->changeColumn('allow_borrow_adddelete', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'allow_laundry_block',
            ])
            ->save();
        $this->table('cms_usergroups_camps', [
                'id' => false,
                'engine' => 'InnoDB',
                'encoding' => 'utf8',
                'collation' => 'utf8_general_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
        ->changeColumn('camp_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
            ])
        ->changeColumn('cms_usergroups_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'after' => 'camp_id',
            ])
            ->save();
        $this->table('cms_usergroups_functions', [
                'id' => false,
                'engine' => 'InnoDB',
                'encoding' => 'utf8',
                'collation' => 'utf8_general_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
        ->changeColumn('cms_functions_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
            ])
        ->changeColumn('cms_usergroups_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'after' => 'cms_functions_id',
            ])
            ->save();
        $this->table('organisations', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8',
                'collation' => 'utf8_general_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
        ->changeColumn('deleted', 'datetime', [
                'null' => true,
                'after' => 'created_by',
            ])
        ->changeColumn('modified', 'datetime', [
                'null' => true,
                'after' => 'deleted',
            ])
        ->changeColumn('modified_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'modified',
            ])
            ->save();
        $this->table('product_categories', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8',
                'collation' => 'utf8_general_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('parent_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'after' => 'seq',
            ])
            ->save();
        $this->table('products', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8',
                'collation' => 'utf8_general_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
        ->changeColumn('category_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'after' => 'name',
            ])
        ->changeColumn('gender_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'category_id',
            ])
        ->changeColumn('sizegroup_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'after' => 'gender_id',
            ])
        ->changeColumn('camp_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'after' => 'sizegroup_id',
            ])
        ->changeColumn('value', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'camp_id',
            ])
        ->changeColumn('amountneeded', 'float', [
                'null' => false,
                'default' => '1',
                'after' => 'value',
            ])
        ->changeColumn('created', 'datetime', [
                'null' => true,
                'after' => 'amountneeded',
            ])
        ->changeColumn('created_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'created',
            ])
        ->changeColumn('modified', 'datetime', [
                'null' => true,
                'after' => 'created_by',
            ])
        ->changeColumn('modified_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'modified',
            ])
        ->changeColumn('maxperadult', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'modified_by',
            ])
        ->changeColumn('maxperchild', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'maxperadult',
            ])
        ->changeColumn('stockincontainer', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'maxperchild',
            ])
        ->changeColumn('comments', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'stockincontainer',
            ])
        ->changeColumn('deleted', 'datetime', [
                'null' => true,
                'after' => 'comments',
            ])
            ->removeColumn('groupname')
        ->addIndex(['category_id'], [
                'name' => 'category_id',
                'unique' => false,
            ])
        ->addIndex(['gender_id'], [
                'name' => 'gender_id',
                'unique' => false,
            ])
        ->addIndex(['sizegroup_id'], [
                'name' => 'sizegroup_id',
                'unique' => false,
            ])
        ->addIndex(['camp_id'], [
                'name' => 'camp_id',
                'unique' => false,
            ])
            ->save();
        $this->table('sizes', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8',
                'collation' => 'utf8_general_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
        ->changeColumn('sizegroup_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'after' => 'label',
            ])
        ->addIndex(['sizegroup_id'], [
                'name' => 'sizegroup_id',
                'unique' => false,
            ])
            ->save();
    }
}
