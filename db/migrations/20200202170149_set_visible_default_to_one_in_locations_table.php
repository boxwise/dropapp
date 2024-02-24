<?php

use Phinx\Migration\AbstractMigration;

class SetVisibleDefaultToOneInLocationsTable extends AbstractMigration
{
    public function change(): void
    {
        $this->execute('ALTER TABLE locations ALTER COLUMN visible SET DEFAULT 1');
    }
}
