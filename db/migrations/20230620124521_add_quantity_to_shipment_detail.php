<?php

use Phinx\Migration\AbstractMigration;

class AddQuantityToShipmentDetail extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('shipment_detail');
        $table
            ->addColumn('source_quantity', 'integer', [
                'null' => false,
                'signed' => true,
                'after' => 'target_product_id',
            ])
            ->addColumn('target_quantity', 'integer', [
                'null' => true,
                'signed' => true,
                'after' => 'source_quantity',
            ])
            ->update()
        ;
    }
}
