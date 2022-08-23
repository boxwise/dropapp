<?php

use Phinx\Migration\AbstractMigration;

class AddDistroEventTrackingGroupIdForeignKey extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('distro_events_tracking_logs');
        $table->addColumn('distro_event_tracking_group_id', 'integer', [
            'null' => true,
            'signed' => false,
            'default' => null,
            'after' => 'flow_direction',
        ])
            ->addForeignKey('distro_event_tracking_group_id', 'distro_events_tracking_groups', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])->save();
    }
}
