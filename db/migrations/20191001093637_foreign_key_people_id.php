<?php

use Phinx\Migration\AbstractMigration;

class ForeignKeyPeopleId extends AbstractMigration
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
    public function change()
    {
        $this->table('borrow_transactions')
            ->addForeignKey('people_id', 'people', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
        ;
        $this->table('laundry_appointments')
            ->changeColumn('people_id', 'integer', [
                'signed' => true,
            ])
            ->addForeignKey('people_id', 'people', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
        ;
        $this->table('library_transactions')
            ->changeColumn('people_id', 'integer', [
                'signed' => true,
            ])
            ->addForeignKey('people_id', 'people', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
        ;
        $this->table('x_people_languages')
            ->addForeignKey('people_id', 'people', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
        ;
    }
}
