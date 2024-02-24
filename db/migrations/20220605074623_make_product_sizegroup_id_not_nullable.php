<?php

use Phinx\Migration\AbstractMigration;

class MakeProductSizegroupIdNotNullable extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('products');

        $table->changeColumn('sizegroup_id', 'integer', ['null' => false, 'signed' => false])
            ->update()
        ;
    }

    public function down(): void
    {
        $table = $this->table('products');

        $table->changeColumn('sizegroup_id', 'integer', [
            'null' => true,
            'default' => null,
            'signed' => false,
        ])->update();
    }
}
