<?php

use Phinx\Migration\AbstractMigration;

class DirstroEventsPackingListEntries extends AbstractMigration
{
    public function change()
    {
        $distroEventsPackingListEntries = $this->table('distro_events_packing_list_entries', [
            'id' => true,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'signed' => false,
            'encoding' => 'utf8mb4',
            'collation' => 'utf8_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ]);
        $distroEventsPackingListEntries->addColumn('product_id', 'integer', [
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

            ->addColumn('state', 'string', [
                'null' => false,
                'limit' => '255',
                'after' => 'distro_event_id',
            ])
            ->addColumn('created_on', 'datetime', [
                'null' => false,
                'after' => 'state',
            ])
            ->addColumn('created_by', 'integer', [
                'null' => false,
                'signed' => false,
                'after' => 'created_on',
            ])
            ->addColumn('last_modified_on', 'datetime', [
                'null' => true,
                'after' => 'created_by',
            ])
            ->addColumn('last_modified_by', 'integer', [
                'null' => true,
                'signed' => false,
                'after' => 'last_modified_on',
            ])
            ->addForeignKey('distro_event_id', 'distro_events', 'id', [
                'update' => 'CASCADE',
            ])
            ->addForeignKey('product_id', 'products', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->addForeignKey('size_id', 'sizes', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->addForeignKey('created_by', 'cms_users', 'id', [
                'update' => 'CASCADE',
            ])
            ->addForeignKey('last_modified_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->addIndex(['product_id'], ['name' => 'distro_events_packing_list_entries_product_id'])
            ->addIndex(['size_id'], ['name' => 'distro_events_packing_list_entries_size_id'])
            ->addIndex(['state'], ['name' => 'distro_events_packing_list_entries_state'])
            ->addIndex(['created_by'], ['name' => 'distro_events_packing_list_entries_created_by'])
            ->addIndex(['last_modified_by'], ['name' => 'distro_events_packing_list_entries_last_modified_by'])
            ->create()
        ;
    }
}
