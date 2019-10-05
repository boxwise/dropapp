<?php

use Phinx\Migration\AbstractMigration;

class ForeignKeyQr extends AbstractMigration
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
    public function change()
    {
        $this->table('stock')
            ->changeColumn('qr_id', 'integer', [
                'signed' => false,
                'null' => true,
            ])
            ->save();
        $this->execute('UPDATE stock SET qr_id = NULL WHERE qr_id = 0');
        $this->table('stock')
            ->addForeignKey('qr_id', 'qr', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
        ;
        $this->table('translate')
            ->changeColumn('category_id', 'integer', [
                'signed' => false,
                'null' => true,
            ])->save();
        $this->execute('update translate SET category_id = NULL WHERE category_id not in (select id from translate_categories)');
        $this->table('translate')
            ->addForeignKey('category_id', 'translate_categories', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
    ;
    }
}
