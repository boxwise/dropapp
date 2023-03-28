<?php

use Phinx\Migration\AbstractMigration;

class AddReceivedOnAsShipmentState extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('shipment');
        $table->addColumn('receiving_started_on', 'datetime', [
            'null' => true,
            'after' => 'sent_by_id',
        ])
            ->addColumn('receiving_started_by_id', 'integer', [
                'null' => true,
                'signed' => false,
                'after' => 'receiving_started_on',
            ])
            ->addForeignKey('receiving_started_by_id', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->addIndex(['receiving_started_by_id'], ['name' => 'shipment_receiving_started_by_id'])
            ->update()
        ;
    }
}
