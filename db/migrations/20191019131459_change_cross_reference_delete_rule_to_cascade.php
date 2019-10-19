<?php

use Phinx\Migration\AbstractMigration;

class ChangeCrossReferenceDeleteRuleToCascade extends AbstractMigration
{
    public function change()
    {
        $this->table('x_people_languages')
            ->dropForeignKey('people_id')
            ->dropForeignKey('language_id')
            ->addForeignKey('people_id', 'people', 'id', [
                'delete' => 'CASCADE', 'update' => 'CASCADE',
            ])
            ->addForeignKey('language_id', 'languages', 'id', [
                'delete' => 'CASCADE', 'update' => 'CASCADE',
            ])
            ->save();
    }
}
