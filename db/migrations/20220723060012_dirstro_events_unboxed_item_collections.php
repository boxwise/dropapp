<?php

use Phinx\Migration\AbstractMigration;

class DirstroEventsUnboxedItemCollections extends AbstractMigration
{
    public function change()
    {
        $distroEventsUnboxedItemCollections = $this->table('distro_events_unboxed_item_collections', [
            'id' => true,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'signed' => false,
            'encoding' => 'utf8mb4',
            'collation' => 'utf8_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ]);
        $distroEventsUnboxedItemCollections->addColumn('product_id', 'integer', [
            'null' => false,
            'signed' => false,
            'after' => 'id',
        ])
            ->addColumn('number_of_items', 'integer', [
                'null' => false,
                'after' => 'product_id',
            ])
            ->addColumn('size_id', 'integer', [
                'null' => false,
                'signed' => false,
                'after' => 'number_of_items',
            ])
            ->addColumn('distro_event_id', 'integer', [
                'null' => false,
                'signed' => false,
                'after' => 'size_id',
            ])
            ->addColumn('location_id', 'integer', [
                'null' => false,
                'signed' => false,
                'after' => 'distro_event_id',
            ])
            ->addColumn('box_state_id', 'integer', [
                'null' => false,
                'signed' => false,
                'default' => 1,
                'after' => 'location_id',
            ])

            ->addColumn('comments', 'string', [
                'null' => true,
                'limit' => '255',
                'after' => 'box_state_id',
            ])
            ->addColumn('created_on', 'datetime', [
                'null' => false,
                'after' => 'comments',
            ])
            ->addColumn('created_by', 'integer', [
                'null' => false,
                'signed' => false,
                'after' => 'created_on',
            ])
            ->addColumn('modified_on', 'datetime', [
                'null' => true,
                'after' => 'created_by',
            ])
            ->addColumn('modified_by', 'integer', [
                'null' => true,
                'signed' => false,
                'after' => 'modified_on',
            ])
            ->addForeignKey('distro_event_id', 'distro_events', 'id', [
                'update' => 'CASCADE',
            ])
            ->addForeignKey('product_id', 'products', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->addForeignKey('box_state_id', 'box_state', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->addForeignKey('size_id', 'sizes', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->addForeignKey('location_id', 'locations', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->addForeignKey('created_by', 'cms_users', 'id', [
                'update' => 'CASCADE',
            ])
            ->addForeignKey('modified_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->addIndex(['product_id'], ['name' => 'distro_events_unboxed_item_collections_product_id'])
            ->addIndex(['size_id'], ['name' => 'distro_events_unboxed_item_collections_size_id'])
            ->addIndex(['location_id'], ['name' => 'distro_events_unboxed_item_collections_location_id'])
            ->addIndex(['box_state_id'], ['name' => 'distro_events_unboxed_item_collections_box_state_id'])
            ->addIndex(['created_by'], ['name' => 'distro_events_unboxed_item_collections_created_by'])
            ->addIndex(['modified_by'], ['name' => 'distro_events_unboxed_item_collections_modified_by'])
            ->create()
        ;
    }
}
