<?php

use Phinx\Migration\AbstractMigration;

class CleanUpBorrow extends AbstractMigration
{
    public function up()
    {
        $this->table('borrow_categories')->drop()->save();
        $this->table('borrow_items')->drop()->save();
        $this->table('borrow_locations')->drop()->save();
        $this->table('borrow_transactions')->drop()->save();
    }

    // reverse
    public function down()
    {
        $this->table('borrow_categories', [
            'id' => false,
            'primary_key' => ['id'],
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
            ])
            ->create()
        ;
        $this->table('borrow_items', [
            'id' => false,
            'primary_key' => ['id'],
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
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
            ])
            ->addColumn('category_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
            ])
            ->addColumn('visible', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
            ])
            ->addColumn('comment', 'text', [
                'null' => false,
                'limit' => 65535,
            ])
            ->addColumn('created', 'datetime', [
                'null' => true,
            ])
            ->addColumn('created_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
            ])
            ->addColumn('modified', 'datetime', [
                'null' => true,
            ])
            ->addColumn('modified_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
            ])
            ->addColumn('location_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
            ])
            ->create()
        ;
        $this->table('borrow_locations', [
            'id' => false,
            'primary_key' => ['id'],
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
            ])
            ->addColumn('location', 'string', [
                'null' => true,
                'limit' => 255,
            ])
            ->create()
        ;
        $this->table('borrow_transactions', [
            'id' => false,
            'primary_key' => ['id'],
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => 'enable',
            ])
            ->addColumn('transaction_date', 'datetime', [
                'null' => true,
            ])
            ->addColumn('bicycle_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
            ])
            ->addColumn('people_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
            ])
            ->addColumn('status', 'string', [
                'null' => true,
                'limit' => 5,
            ])
            ->addColumn('created', 'datetime', [
                'null' => true,
            ])
            ->addColumn('created_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
            ])
            ->addColumn('modified', 'datetime', [
                'null' => true,
            ])
            ->addColumn('modified_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
            ])
            ->addColumn('lights', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
            ])
            ->addColumn('helmet', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
            ])
            ->addColumn('location_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
            ])
            ->create()
        ;
    }
}
