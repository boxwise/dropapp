<?php

use Phinx\Migration\AbstractMigration;

class AddBoxMonetaryValueWeight extends AbstractMigration
{
    public function up(): void
    {
        $this->table('stock')
            ->addColumn('weight_display_unit_id', 'integer', [
                'null' => false,
                'default' => 1,  // kilogram
                'signed' => false,
                'after' => 'size_id',
            ])
            ->addColumn('weight', 'decimal', [
                'null' => true,
                'default' => null,
                'signed' => false,
                // Values for DECIMAL columns are stored using a binary format
                // that packs nine decimal digits into 4 bytes
                'precision' => 36,
                'scale' => 18,
                'after' => 'weight_display_unit_id',
            ])
            ->addColumn('monetary_value', 'decimal', [
                'null' => true,
                'default' => null,
                'signed' => false,
                'precision' => 36,
                'scale' => 18,
                'after' => 'weight',
            ])
            ->save()
        ;
        $this->table('stock')
            ->addForeignKey('weight_display_unit_id', 'units', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
        ;
        $this->table('camps')
            ->addColumn('monetary_currency_code', 'string', [
                'null' => false,
                'default' => 'EUR',
                'limit' => 15,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'currencyname',
            ])
            ->save()
        ;
    }

    public function down(): void
    {
        $this->table('stock')
            ->dropForeignKey('weight_display_unit_id')
            ->removeColumn('weight_display_unit_id')
            ->removeColumn('weight')
            ->removeColumn('monetary_value')
            ->save()
        ;
        $this->table('camps')
            ->removeColumn('monetary_currency_code')
            ->save()
        ;
    }
}
