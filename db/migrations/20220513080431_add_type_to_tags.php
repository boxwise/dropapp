<?php

use Phinx\Migration\AbstractMigration;

class AddTypeToTags extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up(): void
    {
        $this->table('tags')
            ->addColumn('type', 'string', [
                'null' => true,
                'default' => 'People',
                'limit' => '255',
            ])->addIndex(['type'], ['name' => 'tags_type_index', 'unique' => false])
            ->save()
        ;
    }

    /**
     * Migrate Down.
     */
    public function down(): void
    {
        $this->table('tags')
            ->removeColumn('type')->update()
        ;
    }
}
