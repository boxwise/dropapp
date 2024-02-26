<?php

use Phinx\Migration\AbstractMigration;

class FixUsergroupsForeignKey extends AbstractMigration
{
    public function change(): void
    {
        $hasforeignkey = $this->table('cms_usergroups')
            ->hasForeignKey('userlevel')
        ;
        if ($hasforeignkey) {
            $this->table('cms_usergroups')
                ->dropForeignKey('userlevel')
                ->save()
            ;
        }
        $this->table('cms_usergroups')
            ->addForeignKey('userlevel', 'cms_usergroups_levels', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
        ;
    }
}
