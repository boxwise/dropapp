<?php

use Phinx\Migration\AbstractMigration;

class DistroEventsTrackingGroups extends AbstractMigration
{
    public function change()
    {
        $distroEventsTrackingGroups = $this->table('distro_events_tracking_groups', [
            'id' => true,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'signed' => false,
            'encoding' => 'utf8',
            'collation' => 'utf8_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ]);
        $distroEventsTrackingGroups->addColumn('group_name', 'string', [
            'null' => true,
            'limit' => '255',
            'after' => 'id',
        ])
            ->addColumn('base_id', 'integer', [
                'null' => false,
                'signed' => false,
                'after' => 'group_name',
            ])
            ->addColumn('state', 'string', [
                'null' => false,
                'default' => 'In Progress',
                'limit' => '255',
                'after' => 'base_id',
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
            ->addColumn('modified_on', 'datetime', [
                'null' => true,
                'after' => 'created_by',
            ])
            ->addColumn('modified_by', 'integer', [
                'null' => true,
                'signed' => false,
                'after' => 'modified_on',
            ])

            ->addForeignKey('base_id', 'camps', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->addForeignKey('created_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->addForeignKey('modified_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->addIndex(['state'], ['name' => 'distro_events_tracking_groups_state'])
            ->addIndex(['created_on'], ['name' => 'distro_events_tracking_groups_created_on'])
            ->addIndex(['modified_on'], ['name' => 'distro_events_tracking_groups_modified_on'])
            ->create()
        ;
    }
}
