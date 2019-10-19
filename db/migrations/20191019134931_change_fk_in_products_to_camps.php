<?php

use Phinx\Migration\AbstractMigration;

class ChangeFkInProductsToCamps extends AbstractMigration
{
    public function change()
    {
        $this->table('products')
            ->dropForeignKey('camp_id')
            ->addForeignKey('camp_id', 'camps', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
        ;
    }
}
