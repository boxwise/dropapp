<?php

use Phinx\Migration\AbstractMigration;

class DropProductAmountneededColumn extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('products');
        if ($table->hasColumn('amountneeded')) {
            $table->removeColumn('amountneeded')
                ->update()
            ;
        }
    }

    public function down()
    {
        $table = $this->table('products');
        if (!$table->hasColumn('amountneeded')) {
            $table->addColumn('amountneeded', 'float', [
                'null' => false,
                'default' => '1',
                'after' => 'value',
            ])->update();
        }
    }
}
