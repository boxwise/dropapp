<?php

use Phinx\Migration\AbstractMigration;

class AddDistroEventsColumnsToLocations extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('locations');
        $table->addColumn('type', 'string', [
            'null' => false,
            'default' => 'Warehouse',
            'limit' => '20',
            'after' => 'is_lost',
        ])
            ->addColumn('latitude', \Phinx\Db\Adapter\MysqlAdapter::PHINX_TYPE_DOUBLE, [
                'null' => true,
                'after' => 'type',
            ])->addColumn('longitude', \Phinx\Db\Adapter\MysqlAdapter::PHINX_TYPE_DOUBLE, [
                'null' => true,
                'after' => 'latitude',
            ])->addColumn('description', 'string', [
                'null' => true,
                'default' => null,
                'limit' => '255',
                'after' => 'longitude',
            ])->addIndex(['type'], ['name' => 'locations_type_index', 'unique' => false])
            ->addIndex(['latitude'], ['name' => 'locations_latitude_index', 'unique' => false])
            ->addIndex(['longitude'], ['name' => 'locations_longitude_index', 'unique' => false])
            ->save()
        ;
    }
}
