<?php

use Phinx\Migration\AbstractMigration;

class RenameDistroEventsOutflowLogs extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up(): void
    {
        $table = $this->table('distro_events_outflow_logs');
        $table
            ->rename('distro_events_tracking_logs')
            ->update()
        ;
    }

    /**
     * Migrate Down.
     */
    public function down(): void
    {
        $table = $this->table('distro_events_tracking_logs');
        $table
            ->rename('distro_events_outflow_logs')
            ->update()
        ;
    }
}
