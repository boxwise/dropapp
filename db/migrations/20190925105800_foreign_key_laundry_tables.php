<?php

use Phinx\Migration\AbstractMigration;

class ForeignKeyLaundryTables extends AbstractMigration
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
        $this->table('laundry_slots')
            ->changeColumn('machine', 'integer', [
                'signed' => false,
                'null' => false,
            ])
            ->changeColumn('time', 'integer', [
                'signed' => false,
            ])
            ->addForeignKey('machine', 'laundry_machines', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->addForeignKey('time', 'laundry_times', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
        ;
        $this->table('laundry_appointments')
            ->changeColumn('timeslot', 'integer', [
                'signed' => false,
            ])
            ->addForeignKey('timeslot', 'laundry_slots', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
        ;
        $this->table('laundry_machines')
            ->changeColumn('station', 'integer', [
                'signed' => false,
            ])
            ->addForeignKey('station', 'laundry_stations', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
        ;
    }
}
