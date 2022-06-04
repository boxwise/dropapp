<?php

use Phinx\Migration\AbstractMigration;

class MakeItemsInStockNullable extends AbstractMigration
{
    public function up()
    {
        $stock = $this->table('stock');
        $stock->changeColumn('items', 'integer', ['null' => true])
            ->update()
        ;
    }

    public function down()
    {
        $stock = $this->table('stock');
        $stock->changeColumn('items', 'integer', ['null' => false])
            ->update()
        ;
    }
}
