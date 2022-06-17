<?php

use Phinx\Migration\AbstractMigration;

class AddProductCategoryIdForeignKey extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('products');
        $table->addForeignKey('category_id', 'product_categories', 'id', [
            'delete' => 'RESTRICT', 'update' => 'CASCADE',
        ])->update();
    }
}
