<?php

use Phinx\Migration\AbstractMigration;

class DropDistroEventIdForeignKey extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('distro_events_outflow_logs');
        $table->dropForeignKey('distro_event_id')
            ->removeColumn('distro_event_id')
            ->update()
        ;
    }

    public function down()
    {
        $table = $this->table('distro_events_outflow_logs');
        $table->addColumn('distro_event_id', 'integer', [
            'null' => true,
            'signed' => false,
            'default' => null,
            'after' => 'location_id',
        ])
            ->addForeignKey('distro_event_id', 'distro_events', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])->save()
        ;
    }
}
