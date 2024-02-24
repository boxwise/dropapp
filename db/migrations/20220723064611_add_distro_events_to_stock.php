<?php

use Phinx\Migration\AbstractMigration;

class AddDistroEventsToStock extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('stock');
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
