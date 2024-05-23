<?php

use Phinx\Migration\AbstractMigration;

class AddStandardProductForeignKey extends AbstractMigration
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
        # Add virtual column to enable unique composite index on (camp_id,
        # not_deleted, standard_product_id). Using the `deleted` column alone
        # doesn't work since MySQL ignores tuples with a NULL value for
        # uniqueness constraints.
        # With the following helper column, a standard product cannot be
        # enabled if already enabled for the base
        $this->execute('ALTER TABLE products
            ADD COLUMN not_deleted BOOLEAN
            AS (IF(deleted IS NULL, 1, NULL)) STORED;')
        ;
        $this->table('products')
            ->addIndex(['camp_id', 'not_deleted', 'standard_product_id'], ['unique' => true])
            ->save()
        ;
    }

    public function down(): void
    {
        $this->table('products')
            ->removeIndex(['camp_id', 'not_deleted', 'standard_product_id'])
            ->removeColumn('not_deleted')
            ->removeIndex(['standard_product_id'])
            ->dropForeignKey('standard_product_id')
            ->removeColumn('standard_product_id')
            ->save()
        ;
    }
}
