<?php

use Phinx\Migration\AbstractMigration;

class AddTemporalColumnsToTagsRelations extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('tags_relations');
        $table
            ->addColumn('created_on', 'datetime', [
                'null' => true,
                'after' => 'tag_id',
            ])
            ->addColumn('created_by_id', 'integer', [
                'null' => true,
                'signed' => false,
                'after' => 'created_on',
            ])
            ->addColumn('deleted_on', 'datetime', [
                'null' => true,
                'after' => 'created_by_id',
            ])
            ->addColumn('deleted_by_id', 'integer', [
                'null' => true,
                'signed' => false,
                'after' => 'deleted_on',
            ])
            ->addForeignKey('created_by_id', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->addForeignKey('deleted_by_id', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->update()
        ;
        // Drop the composite primary key (object_id, tag_id, object_type)
        $this->execute('ALTER TABLE tags_relations DROP PRIMARY KEY');
        // Add integer PK to have table ordering, cf. https://stackoverflow.com/a/9070808/3865876
        $this->execute('ALTER TABLE tags_relations ADD id INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST');
    }

    public function down(): void
    {
        $this->execute('ALTER TABLE tags_relations DROP PRIMARY KEY');
        $this->table('tags_relations')
            ->dropForeignKey('created_by_id')
            ->removeColumn('created_by_id')
            ->dropForeignKey('deleted_by_id')
            ->removeColumn('deleted_by_id')
            ->removeColumn('created_on')
            ->removeColumn('deleted_on')
            ->removeColumn('id')
            ->save()
        ;
        $this->execute('ALTER TABLE tags_relations ADD PRIMARY KEY(object_id,tag_id,object_type)');
    }
}
