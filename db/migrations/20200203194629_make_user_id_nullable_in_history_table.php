<?php

use Phinx\Migration\AbstractMigration;

class MakeUserIdNullableInHistoryTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('history')
            ->changeColumn('user_id', 'integer', [
                'null' => true,
            ])->save()
        ;
    }
}
