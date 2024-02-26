<?php

use Phinx\Migration\AbstractMigration;

class AddRemovedLostReceivedShipmentDetailFields extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('shipment_detail');
        $table->addColumn('lost_on', 'datetime', [
            'null' => true,
            'after' => 'created_by_id',
        ])
            ->addColumn('lost_by_id', 'integer', [
                'null' => true,
                'signed' => false,
                'after' => 'lost_on',
            ])
            ->addForeignKey('lost_by_id', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->addColumn('received_on', 'datetime', [
                'null' => true,
                'after' => 'lost_by_id',
            ])
            ->addColumn('received_by_id', 'integer', [
                'null' => true,
                'signed' => false,
                'after' => 'received_on',
            ])
            ->addForeignKey('received_by_id', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->update()
        ;
    }
}
