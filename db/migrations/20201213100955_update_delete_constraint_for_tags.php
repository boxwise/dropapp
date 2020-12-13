<?php

use Phinx\Migration\AbstractMigration;

class UpdateDeleteConstraintForTags extends AbstractMigration
{
    public function change()
    {
        $this->table('people_tags')
            ->dropForeignKey('people_id')
            ->addForeignKey('people_id', 'people', 'id', [
                'delete' => 'CASCADE', 'update' => 'CASCADE',
            ])
            ->dropForeignKey('tag_id')
            ->addForeignKey('tag_id', 'tags', 'id', [
                'delete' => 'CASCADE', 'update' => 'CASCADE',
            ])
            ->save()
    ;
    }
}
