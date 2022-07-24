<?php

use Phinx\Migration\AbstractMigration;

class DirstroEventsOutflowLogs extends AbstractMigration
{
    public function change()
    {
        $distroEventsOutflowLogs = $this->table('distro_events_outflow_logs', [
            'id' => true,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'signed' => false,
            'encoding' => 'utf8',
            'collation' => 'utf8_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ]);
        $distroEventsOutflowLogs->addColumn('product_id', 'integer', [
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
            ->addColumn('date', 'datetime', [
                'null' => false,
                'after' => 'location_id',
            ])

            ->addColumn('details', 'text', [
                'null' => true,
                'after' => 'date',
            ])
            ->addColumn('created_on', 'datetime', [
                'null' => false,
                'after' => 'details',
            ])
            ->addColumn('created_by', 'integer', [
                'null' => true,
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
                'update' => 'CASCADE', 'delete' => 'RESTRICT',
            ])
            ->addForeignKey('product_id', 'products', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->addForeignKey('size_id', 'sizes', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->addForeignKey('location_id', 'locations', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->addForeignKey('created_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->addForeignKey('modified_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->addIndex(['date'], ['name' => 'distro_events_outflow_logs_date'])
            ->addIndex(['created_on'], ['name' => 'distro_events_outflow_logs_created_on'])
            ->addIndex(['modified_on'], ['name' => 'distro_events_outflow_logs_modified_on'])
            ->create()
        ;
    }
}
