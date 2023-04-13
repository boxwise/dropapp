<?php

use Phinx\Migration\AbstractMigration;

class AddShipmentDetailFields extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('shipment_detail');
        $table->addColumn('removed_on', 'datetime', [
            'null' => true,
            'after' => 'deleted_by_id',
        ])
            ->addColumn('removed_by_id', 'integer', [
                'null' => true,
                'signed' => false,
                'after' => 'removed_on',
            ])
            ->addForeignKey('removed_by_id', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->addIndex(['removed_by_id'], ['name' => 'shipment_detail_removed_by_id'])

            ->addColumn('lost_on', 'datetime', [
                'null' => true,
                'after' => 'removed_by_id',
            ])
            ->addColumn('lost_by_id', 'integer', [
                'null' => true,
                'signed' => false,
                'after' => 'lost_on',
            ])
            ->addForeignKey('lost_by_id', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->addIndex(['lost_by_id'], ['name' => 'shipment_detail_lost_by_id'])

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
            ->addIndex(['received_by_id'], ['name' => 'shipment_detail_received_by_id'])
            ->update()
        ;
    }
}
