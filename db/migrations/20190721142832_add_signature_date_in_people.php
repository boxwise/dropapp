<?php

use Phinx\Migration\AbstractMigration;

class AddSignatureDateInPeople extends AbstractMigration
{
    public function change()
    {   
        $this->table('people')
        ->addColumn('date_of_signature', 'datetime', [
            'default'=>0
        ])
        ->save();

    }
}
