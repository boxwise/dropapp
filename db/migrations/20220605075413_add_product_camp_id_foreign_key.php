<?php

use Phinx\Migration\AbstractMigration;

class AddProductCampIdForeignKey extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('products');
        $table->addForeignKey('camp_id', 'camps', 'id', [
            'delete' => 'RESTRICT', 'update' => 'CASCADE',
        ])->save();
    }
}
