<?php

use Phinx\Migration\AbstractMigration;

class RemoveUnderscoreColumns extends AbstractMigration
{
    public function up()
    {
        $stock = $this->table('stock');
        $stock->removeColumn('_type')
        ->removeColumn('_gender')
        ->removeColumn('_size')
        ->update();
    }

    public function down()
    {
        $stock = $this->table('stock');
        $stock->addColumn('_type', 'string', [
            'null' => true,
            'limit' => 255,
            'collation' => 'utf8_general_ci',
            'encoding' => 'utf8',
            'after' => 'comments',
        ])
        ->addColumn('_gender', 'string', [
            'null' => true,
            'limit' => 255,
            'collation' => 'utf8_general_ci',
            'encoding' => 'utf8',
            'after' => '_type',
        ])
        ->addColumn('_size', 'string', [
            'null' => true,
            'limit' => 255,
            'collation' => 'utf8_general_ci',
            'encoding' => 'utf8',
            'after' => '_gender',
        ])->update();
    }
}
