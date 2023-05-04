<?php

use Phinx\Migration\AbstractMigration;

class RemoveDefaultFromSourceSizeIdOnShipmentDetails extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('shipment_detail');
        $table
            ->changeColumn('source_size_id', 'integer', [
                'null' => false,
                'signed' => false,
            ])
            ->update()
        ;
    }

    public function down()
    {
        $table = $this->table('shipment_detail');
        $table
            ->changeColumn('source_size_id', 'integer', [
                'null' => false,
                'default' => 52,
                'signed' => false,
            ])
            ->update()
        ;
    }
}
