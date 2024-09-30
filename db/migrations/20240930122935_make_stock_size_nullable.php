<?php

use Phinx\Migration\AbstractMigration;

class MakeStockSizeNullable extends AbstractMigration
{
    public function up(): void
    {
        $stock = $this->table('stock');
        $stock->changeColumn('size_id', 'integer', ['null' => true])
            ->update()
        ;
    }

    public function down(): void
    {
        $stock = $this->table('stock');
        $stock->changeColumn('size_id', 'integer', ['null' => false])
            ->update()
        ;
    }
}
