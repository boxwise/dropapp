<?php

use Phinx\Migration\AbstractMigration;

class AllowNullForProductDeletedTable extends AbstractMigration
{
    public function change()
    {
        $this->table('products')
            ->changeColumn('deleted', 'datetime', [
                'null'=>true
            ])
            ->save();
    }
}
