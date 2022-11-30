<?php

use Phinx\Migration\AbstractMigration;

class DropProductSizegroupIdForeignKey extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('products');
        if ($table->hasForeignKey('sizegroup_id')) {
            $table->dropForeignKey('sizegroup_id')
                ->update()
            ;
        }
    }

    public function down()
    {
        $table = $this->table('products');
        if (!$table->hasForeignKey('sizegroup_id')) {
            $table->addForeignKey('sizegroup_id', 'sizegroup', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])->update();
        }
    }
}
