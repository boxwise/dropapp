<?php

use Phinx\Migration\AbstractMigration;

class FixPrimaryKeyOfTagsRelations extends AbstractMigration
{
    public function up(): void
    {
        $tags_relations = $this->table('tags_relations');
        $tags_relations
            ->changePrimaryKey(['object_id', 'tag_id', 'object_type'])
            ->update()
        ;
    }

    public function down()
    {
    }
}
