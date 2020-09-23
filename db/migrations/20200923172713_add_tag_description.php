<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class AddTagDescription extends AbstractMigration
{
    public function change()
    {
        $tag = $this->table('tags');
        $tag->addColumn('description', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR])
        ->save();
    }
}
