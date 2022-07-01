<?php

use Phinx\Migration\AbstractMigration;

class AddShipmentDetailTable extends AbstractMigration
{
    public function change()
    {
        $shipmentDetail = $this->table('shipment_detail', [
            'id' => true,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'signed' => false,
            'encoding' => 'utf8mb4',
            'collation' => 'utf8_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ]);
        $shipmentDetail->addColumn('shipment_id', 'integer', [
            'null' => false,
            'signed' => false,
            'after' => 'id',
        ])

            ->addColumn('box_id', 'integer', [
                'null' => false,
                'signed' => false,
                'after' => 'shipment_id',
            ])

            ->addColumn('source_product_id', 'integer', [
                'null' => false,
                'signed' => false,
                'after' => 'box_id',
            ])
            ->addColumn('target_product_id', 'integer', [
                'null' => true,
                'signed' => false,
                'after' => 'source_product_id',
            ])
            ->addColumn('source_location_id', 'integer', [
                'null' => false,
                'signed' => false,
                'after' => 'target_product_id',
            ])
            ->addColumn('target_location_id', 'integer', [
                'null' => true,
                'signed' => false,
                'after' => 'source_location_id',
            ])

            ->addColumn('created_on', 'datetime', [
                'null' => false,
                'after' => 'target_location_id',
            ])
            ->addColumn('created_by_id', 'integer', [
                'null' => false,
                'signed' => false,
                'after' => 'created_on',
            ])
            ->addColumn('deleted_on', 'datetime', [
                'null' => true,
                'after' => 'created_by_id',
            ])
            ->addColumn('deleted_by_id', 'integer', [
                'null' => true,
                'signed' => false,
                'after' => 'deleted_on',
            ])
            ->addForeignKey('shipment_id', 'shipment', 'id', [
                'update' => 'CASCADE',
            ])
            ->addForeignKey('box_id', 'stock', 'id', [
                'update' => 'CASCADE',
            ])
            ->addForeignKey('source_product_id', 'products', 'id', [
                'update' => 'CASCADE',
            ])
            ->addForeignKey('target_product_id', 'products', 'id', [
                'update' => 'CASCADE',
            ])
            ->addForeignKey('source_location_id', 'locations', 'id', [
                'update' => 'CASCADE',
            ])
            ->addForeignKey('target_location_id', 'locations', 'id', [
                'update' => 'CASCADE',
            ])
            ->addForeignKey('created_by_id', 'cms_users', 'id', [
                'update' => 'CASCADE',
            ])
            ->addForeignKey('deleted_by_id', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->addIndex(['shipment_id'], ['name' => 'shipment_detail_shipment_id'])
            ->addIndex(['box_id'], ['name' => 'shipment_detail_box_id'])
            ->addIndex(['source_product_id'], ['name' => 'shipment_detail_source_product_id'])
            ->addIndex(['target_product_id'], ['name' => 'shipment_detail_target_product_id'])
            ->addIndex(['source_location_id'], ['name' => 'shipment_detail_source_location_id'])
            ->addIndex(['target_location_id'], ['name' => 'shipment_detail_target_location_id'])
            ->addIndex(['created_by_id'], ['name' => 'shipment_detail_created_by_id'])
            ->addIndex(['deleted_by_id'], ['name' => 'shipment_detail_deleted_by_id'])
            ->create()
        ;
    }
}
