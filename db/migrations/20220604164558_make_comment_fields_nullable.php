<?php

use Phinx\Migration\AbstractMigration;

class MakeCommentFieldsNullable extends AbstractMigration
{
    public function up()
    {
        $stock = $this->table('stock');
        $stock->changeColumn('comments', 'text', ['null' => true])
              ->update();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $stock = $this->table('stock');
        $stock->changeColumn('comments', 'text', ['null' => false])
              ->update();
    }
}
