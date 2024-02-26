<?php

use Phinx\Migration\AbstractMigration;

class AddProductForeignKeys extends AbstractMigration
{
    public function change(): void
    {
        $this->table('products')
            ->changeColumn('category_id', 'integer', [
                'null' => true,
                'default' => null,
                'signed' => false,
            ])
            ->addForeignKey('category_id', 'product_categories', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->changeColumn('gender_id', 'integer', [
                'null' => true,
                'default' => null,
                'signed' => true,
            ])
            ->addForeignKey('gender_id', 'genders', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->changeColumn('sizegroup_id', 'integer', [
                'null' => true,
                'default' => null,
                'signed' => false,
            ])
            ->addForeignKey('sizegroup_id', 'sizegroup', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->changeColumn('camp_id', 'integer', [
                'null' => true,
                'default' => null,
                'signed' => false,
            ])
            ->addForeignKey('camp_id', 'camps', 'id', [
                'delete' => 'CASCADE', 'update' => 'CASCADE',
            ])
            ->save()
        ;
        $this->table('sizes')
            ->changeColumn('sizegroup_id', 'integer', [
                'null' => true,
                'default' => null,
                'signed' => false,
            ])
            ->addForeignKey('sizegroup_id', 'sizegroup', 'id', [
                'delete' => 'CASCADE', 'update' => 'CASCADE',
            ])
            ->save()
        ;
    }
}
