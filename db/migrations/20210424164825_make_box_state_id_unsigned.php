<?php

use Phinx\Migration\AbstractMigration;

class MakeBoxStateIdUnsigned extends AbstractMigration
{
    public function up()
    {
        $this->table('stock')
            ->dropForeignKey('box_state_id')
            ->save()
        ;

        $this->table('locations')
            ->dropForeignKey('box_state_id')
            ->save()
        ;

        $this->table('box_state')->changeColumn('id', 'integer', ['signed' => false])
            ->save()
        ;

        $this->table('stock')
            ->changeColumn('box_state_id', 'integer', ['signed' => false])
            ->addForeignKey('box_state_id', 'box_state', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        $this->table('locations')->addForeignKey('box_state_id', 'box_state', 'id', [
            'delete' => 'RESTRICT', 'update' => 'CASCADE',
        ])
            ->changeColumn('box_state_id', 'integer', ['signed' => false])
            ->save()
        ;
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
    }
}
