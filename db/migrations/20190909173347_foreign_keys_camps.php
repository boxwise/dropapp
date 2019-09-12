<?php

use Phinx\Migration\AbstractMigration;

class ForeignKeysCamps extends AbstractMigration
{
    public function change()
    {
        $this->table('people')
            ->changeColumn('camp_id', 'integer', [
                'signed' => false,
                'default' => null, ])

            ->addForeignKey('camp_id', 'camps', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
    ;
        $this->table('laundry_stations')
            ->changeColumn('camp_id', 'integer', [
                'signed' => false,
                'null' => false, ])

            ->addForeignKey('camp_id', 'camps', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
    ;
        $this->table('locations')
            ->changeColumn('camp_id', 'integer', [
                'signed' => false,
                'null' => false,
                'default' => null, ])

            ->addForeignKey('camp_id', 'camps', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
    ;
        $this->table('library_type')
            ->changeColumn('camp_id', 'integer', [
                'signed' => false,
                'default' => null, ])

            ->addForeignKey('camp_id', 'camps', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
    ;
        $this->table('library')
            ->changeColumn('camp_id', 'integer', [
                'signed' => false,
                 'default' => null, ])

            ->addForeignKey('camp_id', 'camps', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
    ;
        $this->table('borrow_locations')
            ->changeColumn('camp_id', 'integer', [
                'signed' => false,
                 'null' => false,
                 'default' => null, ])

            ->addForeignKey('camp_id', 'camps', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
    ;
    }
}
