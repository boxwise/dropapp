<?php

use Phinx\Migration\AbstractMigration;

class DropProductCampIdForeignKey extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('products');
        if ($table->hasForeignKey('camp_id')) {
            $table->dropForeignKey('camp_id')
                ->update()
            ;
        }
    }

    public function down()
    {
        $table = $this->table('products');
        if (!$table->hasForeignKey('camp_id')) {
            $table->addForeignKey('camp_id', 'camps', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])->update();
        }
    }
}
