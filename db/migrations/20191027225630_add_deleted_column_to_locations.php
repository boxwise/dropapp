<?php

use Phinx\Migration\AbstractMigration;

class AddDeletedColumnToLocations extends AbstractMigration
{
    public function change(): void
    {
        $this->table('locations')
            ->addColumn('deleted', 'datetime', [
                'null' => true,
                'default' => null,
            ])
            ->save()
        ;
    }
}
