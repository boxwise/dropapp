<?php

use Phinx\Migration\AbstractMigration;

class CleanHistoryTransactions extends AbstractMigration
{
    public function change(): void
    {
        $this->execute(
            '
DELETE
FROM history
WHERE tablename = "transactions"
;'
        );
    }
}
