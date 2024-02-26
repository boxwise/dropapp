<?php

use Phinx\Migration\AbstractMigration;

class AddDistroEventTrackingGroupIdColumn extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('distro_events');
        $table->addColumn('distro_event_tracking_group_id', 'integer', [
            'null' => true,
            'signed' => false,
            'default' => null,
            'after' => 'location_id',
        ])
            ->addForeignKey('distro_event_tracking_group_id', 'distro_events_tracking_groups', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])->save()
        ;
    }
}
