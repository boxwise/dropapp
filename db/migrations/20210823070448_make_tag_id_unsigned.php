<?php

use Phinx\Migration\AbstractMigration;

class MakeTagIdUnsigned extends AbstractMigration
{
    public function up()
    {
        $this->table('people_tags')
            ->dropForeignKey('tag_id')
            ->save()
        ;

        $this->table('tags')
            ->changeColumn('id', 'integer', ['identity' => true, 'signed' => false])
            ->save()
        ;

        $this->table('people_tags')
            ->changeColumn('tag_id', 'integer', ['signed' => false, 'null' => false])
            ->addForeignKey('tag_id', 'tags', 'id', [
                'delete' => 'CASCADE', 'update' => 'CASCADE',
            ])
            ->save()
        ;
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
    }
}
