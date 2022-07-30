<?php

use Phinx\Migration\AbstractMigration;

class MakeProductSizegroupIdNotNullable extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('products');

        $table->changeColumn('sizegroup_id', 'integer', ['null' => false, 'signed' => false])
            ->update()
        ;
    }

    public function down()
    {
        $table = $this->table('products');

        $table->changeColumn('sizegroup_id', 'integer', [
            'null' => true,
            'default' => null,
            'signed' => false,
        ])->update();
    }
}
