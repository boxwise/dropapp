<?php

use Phinx\Migration\AbstractMigration;

class ItemsOutNullable extends AbstractMigration
{
    public function change()
    {
        $this->table('itemsout')
            ->changeColumn('from_location', 'integer', [
                'null' => true,
            ])->save();
    }
}
