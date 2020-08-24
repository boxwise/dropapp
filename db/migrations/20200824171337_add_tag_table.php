<?php

use Phinx\Migration\AbstractMigration;

class AddTagTable extends AbstractMigration
{
    public function change()
    {
        $tag = $this->table('tags');
        $tag->addColumn('label', 'string')
            ->addIndex(['label'], ['unique' => true])
            ->addColumn('color', 'string')
            ->addColumn('camp_id', 'integer', [
                'null' => false,
                'signed' => false,
            ])
            ->addForeignKey('camp_id', 'camps', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->addColumn('deleted', 'datetime', [
                'null' => true,
                'default' => null,
            ])
            ->create()
        ;
    }
}
