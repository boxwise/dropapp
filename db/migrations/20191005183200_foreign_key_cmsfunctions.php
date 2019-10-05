<?php

use Phinx\Migration\AbstractMigration;

class ForeignKeyCmsFunctions extends AbstractMigration
{
    public function change()
    {
        $this->table('cms_functions')
            ->changeColumn('parent_id', 'integer', [
                'null' => true,
            ])
            ->save()
        ;
        $this->execute('UPDATE cms_functions SET parent_id = null where parent_id = 0');

        $this->table('cms_functions')
            ->addForeignKey('parent_id', 'cms_functions', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
;
    }
}
