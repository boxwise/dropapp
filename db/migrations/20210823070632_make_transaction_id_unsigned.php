<?php

use Phinx\Migration\AbstractMigration;

class MakeTransactionIdUnsigned extends AbstractMigration
{
    public function up(): void
    {
        $this->table('transactions')
            ->changeColumn('id', 'integer', ['identity' => true, 'signed' => false])
            ->save()
        ;
    }

    /**
     * Migrate Down.
     */
    public function down() {}
}
