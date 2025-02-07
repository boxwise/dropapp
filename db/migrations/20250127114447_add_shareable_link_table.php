<?php

use Phinx\Migration\AbstractMigration;

class AddShareableLinkTable extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('shareable_link', [
            'id' => true,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'signed' => false,
            'encoding' => 'utf8',
            'collation' => 'utf8_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ]);

        $table->addColumn('code', 'string', [
            'null' => false,
            'limit' => 255,
            'after' => 'id',
        ])
            ->addColumn('valid_until', 'datetime', [
                'null' => true,
                'after' => 'code',
            ])
            ->addColumn('view', 'string', [
                'null' => false,
                'limit' => 255,
                'after' => 'valid_until',
            ])
            ->addColumn('base_id', 'integer', [
                'null' => true,
                'signed' => false,
                'after' => 'view',
            ])
            ->addColumn('url_parameters', 'string', [
                'null' => true,
                'limit' => 2000,
                'after' => 'base_id',
            ])
            ->addColumn('created_on', 'datetime', [
                'null' => false,
                'after' => 'url_parameters',
            ])
            ->addColumn('created_by_id', 'integer', [
                'null' => true,
                'signed' => false,
                'after' => 'created_on',
            ])

            ->addForeignKey('created_by_id', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])

            ->addForeignKey('base_id', 'camps', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])

            ->addIndex(['code'], [
                'unique' => true,
                'name' => 'shareable_link_code',
            ])

            ->create()
        ;
    }

    public function down(): void
    {
        $this->table('shareable_link')->drop()->save();
    }
}
