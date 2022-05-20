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
            //     //$table->dropForeignKey('people_id')->update();

            //     $table->addColumn('object_type', 'string', [
            //         'null' => true,
            //         'default' => 'People',
            //         'limit' => '255',
            //         'after' => 'people_id',
            //     ])->update();
            //     $table->renameColumn('people_id', 'object_id')->update();
            $table->rename('tags_relations')->update();

            //     // $this->execute('ALTER TABLE tags_relations
        //     //         ADD CONSTRAINT chk_object_type
        //     //         CHECK (object_type IN ("People","Stock"))');
        }
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $table = $this->table('tags_relations');
        if ($table) {
            // $table->renameColumn('object_id', 'people_id')->update();

            // $table->addForeignKey('people_id', 'people', 'id', [
            //     'delete' => 'CASCADE', 'update' => 'CASCADE',
            // ])->update();

            // $table->removeColumn('object_type')->save();

            $table->rename('people_tags')->update();

            // $this->execute('ALTER TABLE people_tags
            //         DROP CONSTRAINT chk_object_type');
        }
    }
}
