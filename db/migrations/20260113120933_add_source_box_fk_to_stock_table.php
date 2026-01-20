<?php

use Phinx\Migration\AbstractMigration;

class AddSourceBoxFkToStockTable extends AbstractMigration
{
    public function up(): void
    {
        $this->table('stock')->addColumn('source_box_id', 'integer', [
            'null' => true,
            'signed' => false,
            'after' => 'box_state_id',
        ])
            ->addForeignKey('source_box_id', 'stock', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->save()
        ;
    }

    public function down(): void
    {
        $this->table('stock')->dropForeignKey('source_box_id')->save();
        $this->table('stock')->removeColumn('source_box_id')->save();
    }
}
