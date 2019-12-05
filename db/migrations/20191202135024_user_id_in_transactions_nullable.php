<?php

use Phinx\Migration\AbstractMigration;

class UserIdInTransactionsNullable extends AbstractMigration
{
    public function change()
    {
        $this->table('transactions')
            ->changeColumn('user_id', 'integer', [
                'null' => true,
            ])->save();
    }
}
