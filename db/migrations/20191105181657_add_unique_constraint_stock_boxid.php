<?php

use Phinx\Migration\AbstractMigration;

class AddUniqueConstraintStockBoxid extends AbstractMigration
{
    public function change(): void
    {
        $this->table('stock')
            ->addIndex(['box_id'], ['name' => 'box_id_unique', 'unique' => true])
            ->save()
        ;
    }
}
