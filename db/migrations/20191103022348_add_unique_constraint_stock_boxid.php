<?php

use Phinx\Migration\AbstractMigration;

class AddUniqueConstraintStockBoxid extends AbstractMigration
{
    public function up()
    {
        $this->execute('ALTER TABLE stock ADD CONSTRAINT box_id_unique UNIQUE (box_id);');
    }
}
