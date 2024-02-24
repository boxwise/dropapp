<?php

use Phinx\Migration\AbstractMigration;

class MakePeopleIdInTransactionTableNullable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('transactions')
            ->changeColumn('people_id', 'integer', [
                'null' => true,
            ])->save();
    }
}
