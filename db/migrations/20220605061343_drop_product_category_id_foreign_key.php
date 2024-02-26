<?php

use Phinx\Migration\AbstractMigration;

class DropProductCategoryIdForeignKey extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('products');
        if ($table->hasForeignKey('category_id')) {
            $table->dropForeignKey('category_id')
                ->update()
            ;
        }
    }

    public function down(): void
    {
        $table = $this->table('products');
        if (!$table->hasForeignKey('category_id')) {
            $table->addForeignKey('category_id', 'product_categories', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])->update();
        }
    }
}
