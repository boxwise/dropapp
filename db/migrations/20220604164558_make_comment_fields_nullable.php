<?php

use Phinx\Migration\AbstractMigration;

class MakeCommentFieldsNullable extends AbstractMigration
{
    public function up(): void
    {
        $stock = $this->table('stock');
        $stock->changeColumn('comments', 'text', ['null' => true])
            ->update()
        ;
    }

    public function down(): void
    {
        $stock = $this->table('stock');
        $stock->changeColumn('comments', 'text', ['null' => false])
            ->update()
        ;
    }
}
