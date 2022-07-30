<?php

use Phinx\Migration\AbstractMigration;

class RenamePeopleIdtoObjectIdinTagsRelations extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('tags_relations');
        if ($table) {
            $table->renameColumn('people_id', 'object_id')->update();
        }
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $table = $this->table('tags_relations');
        if ($table) {
            $table->renameColumn('object_id', 'people_id')->update();
        }
    }
}
