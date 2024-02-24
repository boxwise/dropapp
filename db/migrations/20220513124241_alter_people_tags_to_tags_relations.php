<?php

use Phinx\Migration\AbstractMigration;

class AlterPeopleTagsToTagsRelations extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up(): void
    {
        $table = $this->table('people_tags');
        if ($table) {
            $table->dropForeignKey('people_id')->rename('tags_relations')->update();
        }
    }

    /**
     * Migrate Down.
     */
    public function down(): void
    {
        $table = $this->table('tags_relations');
        if ($table) {
            $table->rename('people_tags')->addForeignKey('people_id', 'people', 'id', [
                'delete' => 'CASCADE', 'update' => 'CASCADE',
            ])
                ->update()
            ;
        }
    }
}
