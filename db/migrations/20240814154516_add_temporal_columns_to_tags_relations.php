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
    }

    public function down(): void
    {
        $this->table('tags_relations')
            ->dropForeignKey('created_by_id')
            ->removeColumn('created_by_id')
            ->dropForeignKey('deleted_by_id')
            ->removeColumn('deleted_by_id')
            ->removeColumn('created_on')
            ->removeColumn('deleted_on')
            ->save()
        ;
    }
}
