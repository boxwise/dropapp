<?php

use Phinx\Migration\AbstractMigration;

class AlterPeopleTagsToTagsRelations extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('people_tags');
        if ($table) {
            $table->rename('tags_relations')->update();
        }
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $table = $this->table('tags_relations');
        if ($table) {
            $table->rename('people_tags')->update();
        }
    }
}
