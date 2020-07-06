<?php

use Phinx\Migration\AbstractMigration;

class DropOrganisationIdFromCmsUsers extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('cms_users');
        if ($table->hasColumn('organisation_id')) {
            $table->dropForeignKey('organisation_id')
                ->save()
            ;
            $table->removeColumn('organisation_id')
                ->save()
            ;
        }
    }
}
