<?php

use Phinx\Migration\AbstractMigration;

class UniqueConstraintQrIdInStockTable extends AbstractMigration
{
    public function change(): void
    {
        // add unique constraint to qr_id column
        $this->table('stock')
            ->addIndex(['qr_id'], ['name' => 'qr_id_unique', 'unique' => true])
            ->save()
        ;
    }
}
