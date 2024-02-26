<?php

use Phinx\Migration\AbstractMigration;

class AddSizeToShipmentDetail extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('shipment_detail');
        $table
            ->addColumn('source_size_id', 'integer', [
                'null' => false,
                'signed' => false,
                'default' => 52,
                'after' => 'target_product_id',
            ])
            ->addColumn('target_size_id', 'integer', [
                'null' => true,
                'signed' => false,
                'after' => 'source_size_id',
            ])
            ->addForeignKey('source_size_id', 'sizes', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->addForeignKey('target_size_id', 'sizes', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->update()
        ;
    }
}
