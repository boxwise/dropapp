<?php

use Phinx\Migration\AbstractMigration;

class AddCompositeUniqueIndexForCrossReferenceTables extends AbstractMigration
{
    public function change(): void
    {
        $this->table('x_people_languages')
            ->addIndex(['people_id', 'language_id'], ['name' => 'people_language_unique', 'unique' => true])
            ->save()
        ;
        $this->table('cms_functions_camps')
            ->addIndex(['cms_functions_id', 'camps_id'], ['name' => 'functions_camps_unique', 'unique' => true])
            ->save()
        ;
        $this->table('cms_usergroups_camps')
            ->addIndex(['camp_id', 'cms_usergroups_id'], ['name' => 'usergroups_camps_unique', 'unique' => true])
            ->save()
        ;
        $this->table('cms_usergroups_functions')
            ->addIndex(['cms_functions_id', 'cms_usergroups_id'], ['name' => 'usergroups_functions_unique', 'unique' => true])
            ->save()
        ;
    }
}
