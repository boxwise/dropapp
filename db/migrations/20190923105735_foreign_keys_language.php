<?php

use Phinx\Migration\AbstractMigration;

class ForeignKeysLanguage extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $this->table('cms_users')
            ->changeColumn('language', 'integer', [
                'signed' => false,
                'null' => true, // production data has rows that are null
            ])
            ->addForeignKey('language', 'languages', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
    ;
        $this->table('x_people_languages')
            ->changeColumn('language_id', 'integer', [
                'signed' => false,
                'null' => false,
            ])
            ->addForeignKey('language_id', 'languages', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
    ;
    }
}
