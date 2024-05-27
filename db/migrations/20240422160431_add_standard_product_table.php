<?php

use Phinx\Migration\AbstractMigration;

class AddStandardProductTable extends AbstractMigration
{
    public function up(): void
    {
        $standardProduct = $this->table('standard_product', [
            'id' => true,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'signed' => false,
            'encoding' => 'utf8',
            'collation' => 'utf8_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ]);

        $standardProduct->addColumn('name', 'string', [
            'null' => false,
            'limit' => 255,
            'after' => 'id',
        ])
            ->addColumn('category_id', 'integer', [
                'null' => false,
                'signed' => false,
                'after' => 'name',
            ])
            ->addColumn('gender_id', 'integer', [
                'null' => false,
                'signed' => false,
                'after' => 'category_id',
            ])
            ->addColumn('size_range_id', 'integer', [
                'null' => false,
                'signed' => false,
                'after' => 'gender_id',
            ])
            ->addColumn('version', 'integer', [
                'null' => false,
                'signed' => false,
                'after' => 'size_range_id',
            ])
            ->addColumn('added_on', 'datetime', [
                'null' => false,
                'after' => 'version',
            ])
            ->addColumn('added_by', 'integer', [
                'null' => true,
                'signed' => false,
                'after' => 'added_on',
            ])
            ->addColumn('deprecated_on', 'datetime', [
                'null' => true,
                'after' => 'added_by',
            ])
            ->addColumn('deprecated_by', 'integer', [
                'null' => true,
                'signed' => false,
                'after' => 'deprecated_on',
            ])
            ->addColumn('preceded_by_product_id', 'integer', [
                'null' => true,
                'signed' => false,
                'after' => 'deprecated_by',
            ])
            ->addColumn('superceded_by_product_id', 'integer', [
                'null' => true,
                'signed' => false,
                'after' => 'preceded_by_product_id',
            ])

            ->addForeignKey('category_id', 'product_categories', 'id', [
                'update' => 'CASCADE',
            ])
            ->addForeignKey('gender_id', 'genders', 'id', [
                'update' => 'CASCADE',
            ])
            ->addForeignKey('size_range_id', 'sizegroup', 'id', [
                'update' => 'CASCADE',
            ])
            ->addForeignKey('added_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->addForeignKey('deprecated_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->addForeignKey('preceded_by_product_id', 'standard_product', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->addForeignKey('superceded_by_product_id', 'standard_product', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])

            ->addIndex(['category_id'], ['name' => 'standard_product_category_id'])
            ->addIndex(['gender_id'], ['name' => 'standard_product_gender_id'])
            ->addIndex(['size_range_id'], ['name' => 'standard_product_size_range_id'])
            ->addIndex(['added_by'], ['name' => 'standard_product_added_by'])
            ->addIndex(['deprecated_by'], ['name' => 'standard_product_deprecated_by'])
            ->addIndex(['preceded_by_product_id'], ['name' => 'standard_product_preceded_by_product_id'])
            ->addIndex(['superceded_by_product_id'], ['name' => 'standard_product_superceded_by_product_id'])
            ->create()
        ;
    }

    public function down(): void
    {
        $this->table('standard_product')->drop()->save();
    }
}
