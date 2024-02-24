<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class AddIsScrapFlagToLocationsTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('locations')
            ->addColumn('is_scrap', 'boolean', ['default' => 0, 'null' => false])
            ->changeColumn('container_stock', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_TINY,
                'default' => 0,
            ])
            ->save()
        ;
    }
}
