<?php

use Phinx\Migration\AbstractMigration;

class DropProductCategoryIdForeignKey extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('products');
        if ($table->hasForeignKey('category_id')) {
            $table->dropForeignKey('category_id')
                ->update()
            ;
        }
    }

    public function down()
    {
        $table = $this->table('products');
        if (!$table->hasForeignKey('category_id')) {
            $table->addForeignKey('category_id', 'product_categories', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])->save();
        }
    }
}
