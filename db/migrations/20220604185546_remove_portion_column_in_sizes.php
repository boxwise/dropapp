<?php

use Phinx\Migration\AbstractMigration;

class RemovePortionColumnInSizes extends AbstractMigration
{
    public function up()
    {
        $sizes = $this->table('sizes');
        $sizes->removeColumn('portion')
        ->update();
    }

    public function down()
    {
        $sizes = $this->table('sizes');
        $sizes->addColumn('portion', 'integer', [
            'null' => true,
            'after' => 'sizegroup_id',
        ])->update();
    }
}
