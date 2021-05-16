<?php

use Phinx\Migration\AbstractMigration;

class RemoveUnits extends AbstractMigration
{
    public function up()
    {
        $this->table('units')
            ->drop()
            ->save()
        ;
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
    }
}
