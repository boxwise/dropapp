<?php

use Phinx\Migration\AbstractMigration;

class ChangeFkInSizesToSizegroup extends AbstractMigration
{
    public function change(): void
    {
        $this->table('sizes')
            ->dropForeignKey('sizegroup_id')
            ->addForeignKey('sizegroup_id', 'sizegroup', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
        ;
    }
}
