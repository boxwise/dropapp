<?php

use Phinx\Migration\AbstractMigration;

class AddShipmentTable extends AbstractMigration
{
    public function change()
    {
        $shipment = $this->table('shipment', [
            'id' => true,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'signed' => false,
            'encoding' => 'utf8mb4',
            'collation' => 'utf8_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ]);
        $shipment->addColumn('source_base_id', 'integer', [
            'null' => false,
            'signed' => false,
            'after' => 'id',
        ])
            ->addColumn('target_base_id', 'integer', [
                'null' => false,
                'signed' => false,
                'after' => 'source_base_id',
            ])
            ->addColumn('transfer_agreement_id', 'integer', [
                'null' => false,
                'signed' => false,
                'after' => 'target_base_id',
            ])
            ->addColumn('status', 'string', [
                'null' => false,
                'default' => 'Preparing',
                'limit' => 255,
                'after' => 'transfer_agreement_id',
            ])
            ->addColumn('started_on', 'datetime', [
                'null' => false,
                'after' => 'status',
            ])
            ->addColumn('started_by_id', 'integer', [
                'null' => false,
                'signed' => false,
                'after' => 'started_on',
            ])
            ->addColumn('canceled_on', 'datetime', [
                'null' => true,
                'after' => 'started_by_id',
            ])
            ->addColumn('canceled_by_id', 'integer', [
                'null' => true,
                'signed' => false,
                'after' => 'canceled_on',
            ])
            ->addColumn('sent_on', 'datetime', [
                'null' => true,
                'after' => 'canceled_by_id',
            ])
            ->addColumn('sent_by_id', 'integer', [
                'null' => true,
                'signed' => false,
                'after' => 'sent_on',
            ])
            ->addColumn('completed_on', 'datetime', [
                'null' => true,
                'after' => 'sent_by_id',
            ])
            ->addColumn('completed_by_id', 'integer', [
                'null' => true,
                'signed' => false,
                'after' => 'completed_on',
            ])
            ->addForeignKey('source_base_id', 'camps', 'id', [
                'update' => 'CASCADE',
            ])
            ->addForeignKey('target_base_id', 'camps', 'id', [
                'update' => 'CASCADE',
            ])
            ->addForeignKey('transfer_agreement_id', 'transfer_agreement', 'id', [
                'update' => 'CASCADE',
            ])
            ->addForeignKey('started_by_id', 'cms_users', 'id', [
                'update' => 'CASCADE',
            ])
            ->addForeignKey('canceled_by_id', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->addForeignKey('sent_by_id', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->addForeignKey('completed_by_id', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->addIndex(['source_base_id'], ['name' => 'shipment_source_base_id'])
            ->addIndex(['target_base_id'], ['name' => 'shipment_target_base_id'])
            ->addIndex(['transfer_agreement_id'], ['name' => 'shipment_transfer_agreement_id'])
            ->addIndex(['started_by_id'], ['name' => 'shipment_started_by_id'])
            ->addIndex(['canceled_by_id'], ['name' => 'shipment_canceled_by_id'])
            ->addIndex(['sent_by_id'], ['name' => 'shipment_sent_by_id'])
            ->addIndex(['completed_by_id'], ['name' => 'shipment_completed_by_id'])
            ->create()
        ;
    }
}
