<?php

use Phinx\Migration\AbstractMigration;

class AddProductCategoryIdForeignKey extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('products');
        if (!$table->hasForeignKey('category_id')) {
            $table->addForeignKey('category_id', 'product_categories', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])->save();
        }
    }
}
