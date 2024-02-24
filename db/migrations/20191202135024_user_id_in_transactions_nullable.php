<?php

use Phinx\Migration\AbstractMigration;

class UserIdInTransactionsNullable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('transactions')
            ->changeColumn('user_id', 'integer', [
                'null' => true,
            ])->save();
    }
}
