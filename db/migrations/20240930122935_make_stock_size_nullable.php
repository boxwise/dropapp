<?php

use Phinx\Migration\AbstractMigration;

class MakeStockSizeNullable extends AbstractMigration
{
    public function up(): void
    {
        $stock = $this->table('stock');
        $stock->changeColumn('size_id', 'integer', ['null' => true, 'signed' => false])
            ->update()
        ;
    }

    public function down(): void
    {
        $stock = $this->table('stock');
        $stock->dropForeignKey('size_id')
            ->update()
        ;
        $stock->changeColumn('size_id', 'integer', ['null' => false, 'signed' => false])
            ->addForeignKey('size_id', 'sizes', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->update()
        ;
    }
}
