<?php

use Phinx\Migration\AbstractMigration;

class DirstroEventsTable extends AbstractMigration
{
    public function change()
    {
        $distroEvents = $this->table('distro_events', [
            'id' => true,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'signed' => false,
            'encoding' => 'utf8',
            'collation' => 'utf8_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ]);
        $distroEvents->addColumn('name', 'string', [
            'null' => true,
            'default' => null,
            'limit' => '255',
        ])
            ->addColumn('planned_start_date_time', 'datetime', [
                'null' => false,
                'after' => 'name',
            ])
            ->addColumn('planned_end_date_time', 'datetime', [
                'null' => false,
                'after' => 'planned_start_date_time',
            ])
            ->addColumn('location_id', 'integer', [
                'null' => false,
                'signed' => false,
                'after' => 'planned_start_date_time',
            ])
            ->addColumn('state', 'string', [
                'null' => false,
                'default' => 'Planning',
                'limit' => '255',
                'after' => 'location_id',
            ])
            ->addColumn('created_on', 'datetime', [
                'null' => false,
                'after' => 'state',
            ])
            ->addColumn('created_by', 'integer', [
                'null' => true,
                'signed' => false,
                'after' => 'created_on',
            ])
            ->addColumn('deleted_on', 'datetime', [
                'null' => true,
                'after' => 'created_by',
            ])
            ->addColumn('modified_on', 'datetime', [
                'null' => true,
                'after' => 'deleted_on',
            ])
            ->addColumn('modified_by', 'integer', [
                'null' => true,
                'signed' => false,
                'after' => 'modified_on',
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
            ->addIndex(['planned_start_date_time'], ['name' => 'distro_events_planned_start_date_time'])
            ->addIndex(['planned_end_date_time'], ['name' => 'distro_events_planned_end_date_time'])
            ->addIndex(['state'], ['name' => 'distro_events_state'])
            ->addIndex(['modified_on'], ['name' => 'distro_events_modified_on'])
            ->addIndex(['created_on'], ['name' => 'distro_events_created_on'])
            ->addIndex(['deleted_on'], ['name' => 'distro_events_deleted_on'])
            ->create()
        ;
    }
}
