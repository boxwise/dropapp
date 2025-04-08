<?php

declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class AddServicesTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $tag = $this->table('services');
        $tag->addColumn('label', 'string')
            ->addColumn('description', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR])
            ->addColumn('camp_id', 'integer', [
                'null' => false,
                'signed' => false,
            ])
            ->addForeignKey('camp_id', 'camps', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->addColumn('deleted', 'datetime', [
                'null' => true,
                'default' => null,
            ])
            ->addColumn('created', 'datetime', [
                'null' => true,
            ])
            ->addColumn('created_by', 'integer', [
                'null' => true,
                'signed' => false,
            ])
            ->addForeignKey('created_by', 'cms_users', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->addColumn('modified', 'datetime', [
                'null' => true,
            ])
            ->addColumn('modified_by', 'integer', [
                'null' => true,
                'signed' => false,
            ])
            ->addColumn('seq', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'label',
            ])

            ->addForeignKey('modified_by', 'cms_users', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->create()
        ;
    }
}
