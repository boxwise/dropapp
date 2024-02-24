<?php

use Phinx\Migration\AbstractMigration;

class CreatePeopleTagCrossReference extends AbstractMigration
{
    public function change(): void
    {
        $people_tags = $this->table('people_tags', ['id' => false, 'primary_key' => ['people_id', 'tag_id']]);
        $people_tags->addColumn('people_id', 'integer', [
            'null' => false,
            'signed' => true,
        ])
            ->addForeignKey('people_id', 'people', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->addColumn('tag_id', 'integer', [
                'null' => false,
            ])
            ->addForeignKey('tag_id', 'tags', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->create()
        ;
    }
}
