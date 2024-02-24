<?php

use Phinx\Migration\AbstractMigration;

class MakeProductIdUnsigned extends AbstractMigration
{
    public function up(): void
    {
        $this->table('itemsout')
            ->dropForeignKey('product_id')
            ->save()
        ;

        $this->table('stock')
            ->dropForeignKey('product_id')
            ->save()
        ;

        $this->table('transactions')
            ->dropForeignKey('product_id')
            ->save()
        ;

        $this->table('products')
            ->changeColumn('id', 'integer', ['identity' => true, 'signed' => false])
            ->save()
        ;

        $this->table('itemsout')
            ->changeColumn('product_id', 'integer', ['signed' => false, 'null' => true])
            ->addForeignKey('product_id', 'products', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        $this->table('stock')
            ->changeColumn('product_id', 'integer', ['signed' => false, 'null' => false])
            ->addForeignKey('product_id', 'products', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        $this->table('transactions')
            ->changeColumn('product_id', 'integer', ['signed' => false, 'null' => true])
            ->addForeignKey('product_id', 'products', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
        ;
    }

    /**
     * Migrate Down.
     */
    public function down() {}
}
