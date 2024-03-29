<?php

use Phinx\Migration\AbstractMigration;

class ForeignKeyBorrowTables extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $this->table('borrow_items')
            ->changeColumn('category_id', 'integer', [
                'signed' => false,
                'null' => true,
                'default' => null,
            ])
            ->changeColumn('location_id', 'integer', [
                'signed' => false,
            ])
            ->save()
        ;
        $this->execute('UPDATE borrow_items SET category_id = NULL WHERE category_id = 0');
        $this->table('borrow_items')
            ->addForeignKey('category_id', 'borrow_categories', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->addForeignKey('location_id', 'borrow_locations', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
        ;
        $this->table('borrow_transactions')
            ->changeColumn('location_id', 'integer', [
                'signed' => false,
                'null' => true,
                'default' => null,
            ])
            ->changeColumn('bicycle_id', 'integer', [
                'signed' => false,
            ])
            ->save()
        ;
        $this->execute('UPDATE borrow_transactions SET location_id = NULL WHERE location_id = 0');
        $this->table('borrow_transactions')
            ->addForeignKey('location_id', 'borrow_locations', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->addForeignKey('bicycle_id', 'borrow_items', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->addForeignKey('people_id', 'people', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
        ;
    }
}
