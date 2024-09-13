<?php

use Phinx\Migration\AbstractMigration;

class AddUnitValueToStock extends AbstractMigration
{
    public function up(): void
    {
        $this->table('stock')
            ->addColumn('unit_id', 'integer', [
                'null' => true,
                'default' => null,
                'signed' => false,
                'after' => 'size_id',
            ])
            ->addColumn('measure_value', 'decimal', [
                'null' => true,
                'default' => null,
                'signed' => false,
                // Values for DECIMAL columns are stored using a binary format
                // that packs nine decimal digits into 4 bytes
                'precision' => 36,
                'scale' => 18,
                'after' => 'unit_id',
            ])
            ->addForeignKey('unit_id', 'units', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
        ;
    }

    public function down(): void
    {
        $this->table('products')
            ->dropForeignKey('unit_id')
            ->removeColumn('unit_id')
            ->removeColumn('measure_value')
            ->save()
        ;
    }
}
