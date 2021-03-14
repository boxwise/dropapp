<?php

use Phinx\Migration\AbstractMigration;

class AddTagTable extends AbstractMigration
{
    public function change()
    {
        $tag = $this->table('tags');
        $tag->addColumn('label', 'string')
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
            ->addColumn('created', 'datetime', [
                'null' => true,
            ])
            ->addColumn('created_by', 'integer', [
                'null' => true,
            ])
            ->addForeignKey('created_by', 'cms_users', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->addColumn('modified', 'datetime', [
                'null' => true,
            ])
            ->addColumn('modified_by', 'integer', [
                'null' => true,
            ])
            ->addForeignKey('modified_by', 'cms_users', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->create()
        ;
    }
}
