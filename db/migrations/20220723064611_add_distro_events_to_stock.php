<?php

use Phinx\Migration\AbstractMigration;

class AddDistroEventsToStock extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('stock');
        $table->addColumn('distro_event_id', 'integer', [
            'null' => true,
            'signed' => false,
            'default' => null,
            'after' => 'location_id',
        ])
            ->addIndex(['distro_event_id'], ['name' => 'stock_distro_event_id_index', 'unique' => false])
            ->addForeignKey('distro_event_id', 'distro_events', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])->save()
        ;
    }
}
