<?php

use Phinx\Migration\AbstractMigration;

class AddResettokensColumnToBases extends AbstractMigration
{
    public function change(): void
    {
        $this->table('camps')
            ->addColumn('resettokens', 'boolean', [
                'null' => true,
                'default' => 0,
            ])
            ->save()
        ;
    }
}
