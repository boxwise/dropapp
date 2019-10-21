<?php

use Phinx\Migration\AbstractMigration;

class AddFkForLocationInStockTable extends AbstractMigration
{
    public function change()
    {
        $this->table('stock')
            ->addForeignKey('location_id', 'locations', 'id', ['delete' => 'RESTRICT', 'update' => 'CASCADE'])
            ->save()
        ;
    }
}
