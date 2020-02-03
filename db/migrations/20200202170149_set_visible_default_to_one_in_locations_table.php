<?php

use Phinx\Migration\AbstractMigration;

class SetVisibleDefaultToOneInLocationsTable extends AbstractMigration
{
    public function change()
    {
        $this->execute('ALTER TABLE locations ALTER COLUMN visible SET DEFAULT 1');
    }
}
