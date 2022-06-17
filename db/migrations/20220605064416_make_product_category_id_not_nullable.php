<?php

use Phinx\Migration\AbstractMigration;

class MakeProductCategoryIdNotNullable extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('products');

        $table->changeColumn('category_id', 'integer', ['null' => false, 'signed' => false])
            ->update()
        ;
    }

    public function down()
    {
        $table = $this->table('products');

        $table->changeColumn('category_id', 'integer', [
            'null' => true,
            'default' => null,
            'signed' => false,
        ])->update();
    }
}
