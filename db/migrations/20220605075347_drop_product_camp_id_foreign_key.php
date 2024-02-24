<?php

use Phinx\Migration\AbstractMigration;

class DropProductCampIdForeignKey extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('products');
        if ($table->hasForeignKey('camp_id')) {
            $table->dropForeignKey('camp_id')
                ->update()
                ;
        }
    }

    public function down(): void
    {
        $table = $this->table('products');
        if (!$table->hasForeignKey('camp_id')) {
            $table->addForeignKey('camp_id', 'camps', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])->update();
        }
    }
}
