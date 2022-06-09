<?php

use Phinx\Migration\AbstractMigration;

class AddSeqToTags extends AbstractMigration
{
    public function change()
    {
        $this->table('tags')
            ->addColumn('seq', 'integer', [
                'null' => true,
            ])
            ->save()
        ;
    }
}
