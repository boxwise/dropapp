<?php

use Phinx\Migration\AbstractMigration;

class AddObjectTypeInTagsRelations extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('tags_relations');
        if ($table) {
            $table->addColumn('object_type', 'string', [
                'null' => true,
                'default' => 'People',
                'limit' => '255',
                'after' => 'object_id',
            ])->update();
        }
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $table = $this->table('tags_relations');
        if ($table) {
            $table->removeColumn('object_type')->save();
        }
    }
}
