<?php

use Phinx\Migration\AbstractMigration;

class RemovePortionColumnInSizes extends AbstractMigration
{
    public function up(): void
    {
        $sizes = $this->table('sizes');
        $sizes->removeColumn('portion')
            ->update()
        ;
    }

    public function down(): void
    {
        $sizes = $this->table('sizes');
        $sizes->addColumn('portion', 'integer', [
            'null' => true,
            'after' => 'sizegroup_id',
        ])->update();
    }
}
