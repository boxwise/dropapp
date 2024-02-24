<?php

use Phinx\Migration\AbstractMigration;

class MakeProductCategoryIdNotNullable extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('products');

        $table->changeColumn('category_id', 'integer', ['null' => false, 'signed' => false])
            ->update()
        ;
    }

    public function down(): void
    {
        $table = $this->table('products');

        $table->changeColumn('category_id', 'integer', [
            'null' => true,
            'default' => null,
            'signed' => false,
        ])->update();
    }
}
