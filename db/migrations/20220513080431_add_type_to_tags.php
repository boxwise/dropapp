<?php

use Phinx\Migration\AbstractMigration;

class AddTypeToTags extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->table('tags')
            ->addColumn('type', 'string', [
                'null' => true,
                'default' => 'People',
                'limit' => '255',
            ])->addIndex(['type'], ['name' => 'tags_type_index', 'unique' => false])
            ->save()
        ;

        // $this->execute('ALTER TABLE tags
        // ADD CONSTRAINT chk_tag_type
        // CHECK (`type` IN ("All","People","Stock"))');
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->table('tags')
            ->removeColumn('type')->update();

        // $this->execute('ALTER TABLE tags
        //     DROP CONSTRAINT chk_tag_type');
    }
}
