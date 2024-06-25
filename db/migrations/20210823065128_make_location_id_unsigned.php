<?php

use Phinx\Migration\AbstractMigration;

class MakeLocationIdUnsigned extends AbstractMigration
{
    public function up(): void
    {
        $this->table('stock')
            ->dropForeignKey('location_id')
            ->save()
        ;

        $this->table('itemsout')
            ->dropForeignKey('from_location')
            ->dropForeignKey('to_location')
            ->save()
        ;

        $this->table('locations')
            ->changeColumn('id', 'integer', ['identity' => true, 'signed' => false])
            ->save()
        ;

        $this->table('stock')
            ->changeColumn('location_id', 'integer', ['signed' => false, 'null' => false])
            ->addForeignKey('location_id', 'locations', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        $this->table('itemsout')
            ->changeColumn('from_location', 'integer', ['signed' => false, 'null' => true])
            ->addForeignKey('from_location', 'locations', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->changeColumn('to_location', 'integer', ['signed' => false, 'null' => true])
            ->addForeignKey('to_location', 'locations', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->save()
        ;
    }

    /**
     * Migrate Down.
     */
    public function down() {}
}
