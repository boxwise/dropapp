<?php

use Phinx\Migration\AbstractMigration;

class AddProductSizegroupIdForeignKey extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('products');

        $table->addForeignKey('sizegroup_id', 'sizegroup', 'id', [
            'delete' => 'RESTRICT', 'update' => 'CASCADE',
        ])->update();
    }
}
