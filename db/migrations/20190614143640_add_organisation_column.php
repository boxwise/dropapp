<?php

use Phinx\Migration\AbstractMigration;

class AddOrganisationColumn extends AbstractMigration
{
    public function change()
    {
        $this->table('camps', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8',
            'collation' => 'utf8_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('deleted', 'datetime', [
                'null' => true,
                'after' => 'modified_by',
            ])
            ->save()
        ;
    }
}
