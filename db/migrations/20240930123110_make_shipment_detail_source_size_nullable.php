<?php

use Phinx\Migration\AbstractMigration;

class MakeShipmentDetailSourceSizeNullable extends AbstractMigration
{
    public function up(): void
    {
        $shipment_detail = $this->table('shipment_detail');
        $shipment_detail->changeColumn('source_size_id', 'integer', ['null' => true, 'signed' => false])
            ->update()
        ;
    }

    public function down(): void
    {
        $shipment_detail = $this->table('shipment_detail');
        $shipment_detail->dropForeignKey('source_size_id')
            ->update()
        ;
        $shipment_detail->changeColumn('source_size_id', 'integer', ['null' => false, 'signed' => false])
            ->addForeignKey('source_size_id', 'sizes', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->update()
        ;
    }
}
