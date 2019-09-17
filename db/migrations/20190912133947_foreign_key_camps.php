<?php

use Phinx\Migration\AbstractMigration;

class ForeignKeyCamps extends AbstractMigration
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
        $this->table('people')
            ->changeColumn('camp_id', 'integer', [
                'signed' => false,
                'default' => null,
            ])
            ->addForeignKey('camp_id', 'camps', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        $this->table('locations')
            ->changeColumn('camp_id', 'integer', [
                'null' => false,
                'signed' => false,
            ])
            ->addForeignKey('camp_id', 'camps', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
        ;
        $this->table('laundry_stations')
            ->changeColumn('camp_id', 'integer', [
                'null' => false,
                'signed' => false,
            ])
            ->addForeignKey('camp_id', 'camps', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
    ;
        $this->table('library_type')
            ->changeColumn('camp_id', 'integer', [
                'default' => null,
                'signed' => false,
            ])
            ->addForeignKey('camp_id', 'camps', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
        ;
        $this->table('library')
            ->changeColumn('camp_id', 'integer', [
                'default' => null,
                'signed' => false,
            ])
            ->addForeignKey('camp_id', 'camps', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        $this->table('borrow_locations')
            ->changeColumn('camp_id', 'integer', [
                'null' => false,
                'signed' => false,
            ])
            ->addForeignKey('camp_id', 'camps', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
        ;
    }
}
