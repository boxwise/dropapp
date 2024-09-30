<?php

use Phinx\Migration\AbstractMigration;

class MakeShipmentDetailSourceSizeNullable extends AbstractMigration
{
    public function up(): void
    {
        $shipment_detail = $this->table('shipment_detail');
        $shipment_detail->changeColumn('source_size_id', 'integer', ['null' => true])
            ->update()
        ;
    }

    public function down(): void
    {
        $shipment_detail = $this->table('shipment_detail');
        $shipment_detail->changeColumn('source_size_id', 'integer', ['null' => false])
            ->update()
        ;
    }
}
