<?php

use Phinx\Migration\AbstractMigration;

class AddStandardProductFk extends AbstractMigration
{
    public function up(): void
    {
        $this->table('products')
            ->addColumn('standard_product_id', 'integer', [
                'null' => true,
                'default' => null,
                'signed' => false,
                'after' => 'deleted',
            ])
            ->addForeignKey('standard_product_id', 'standard_product', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->addIndex(['standard_product_id'], ['name' => 'products_standard_product_id'])
            ->save()
        ;
    }

    public function down(): void
    {
        $this->table('products')
            ->removeIndex(['standard_product_id'])
            ->dropForeignKey('standard_product_id')
            ->removeColumn('standard_product_id')
            ->save()
        ;
    }
}
