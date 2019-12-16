<?php

use Phinx\Migration\AbstractMigration;

class AdultAgeInCampsTableNotNull extends AbstractMigration
{
    public function change()
    {
        $this->table('camps')
            ->changeColumn('adult_age', 'integer', [
                'null' => false,
                'default' => 15,
            ])->save();
    }
}
