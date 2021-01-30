<?php

use Phinx\Migration\AbstractMigration;

class AddSettingsForBases extends AbstractMigration
{
    public function change()
    {
        $this->table('camps')
        ->addColumn('beneficiaryisregistered', 'boolean', ['default' => 1, 'null' => false])
        ->addColumn('beneficiaryisvolunteer', 'boolean', ['default' => 1, 'null' => false])
        ->addColumn('separateshopandwhproducts', 'boolean', ['default' => 0, 'null' => false])
        ->save()
    ;
    }
}
