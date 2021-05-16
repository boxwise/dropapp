<?php

use Phinx\Migration\AbstractMigration;

class MakeTransactionsIdUnsigned extends AbstractMigration
{
    public function up()
    {
        $this->table('transactions')
            ->changeColumn('id', 'integer', ['signed' => false])
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
