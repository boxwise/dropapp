<?php

use Phinx\Migration\AbstractMigration;

class DropProductAmountneededColumn extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('products');
        if ($table->hasColumn('amountneeded')) {
            $table->removeColumn('amountneeded')
                ->update()
            ;
        }
    }

    public function down(): void
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
