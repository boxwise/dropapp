<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class RemoveWarehouseOrdering extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('stock');
        $table
            ->dropForeignKey('ordered_by')
            ->dropForeignKey('picked_by')
            ->save()
        ;

        $table
            ->removeColumn('ordered')
            ->removeColumn('ordered_by')
            ->removeColumn('picked')
            ->removeColumn('picked_by')
            ->save()
        ;
    }

    public function down()
    {
        $table = $this->table('stock');
        $table
            ->addColumn('ordered', 'datetime', [
                'null' => true,
                'after' => 'modified_by',
            ])
            ->addColumn('ordered_by', 'integer', [
                'signed' => false,
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
                'signed' => false,
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'picked',
            ])
            ->save()
        ;
        $table
            ->addForeignKey('ordered_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->addForeignKey('picked_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->save()
        ;
    }
}
