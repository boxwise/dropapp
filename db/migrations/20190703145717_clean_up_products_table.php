<?php

use Phinx\Migration\AbstractMigration;

class CleanUpProductsTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('products')
            ->removeColumn('groupname')
            ->changeColumn('value', 'integer', [
                'default' => 0,
            ])
            ->save()
        ;
    }
}
