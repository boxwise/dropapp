<?php

use Phinx\Migration\AbstractMigration;

class MakeBoxStateIdUnsigned extends AbstractMigration
{
    public function up(): void
    {
        $this->table('stock')
            ->dropForeignKey('box_state_id')
            ->save()
        ;

        $this->table('locations')
            ->dropForeignKey('box_state_id')
            ->save()
        ;

        $this->table('box_state')->changeColumn('id', 'integer', ['identity' => true, 'signed' => false])
            ->save()
        ;

        $this->table('stock')
            ->changeColumn('box_state_id', 'integer', ['signed' => false, 'null' => false, 'default' => 1])
            ->addForeignKey('box_state_id', 'box_state', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        $this->table('locations')->addForeignKey('box_state_id', 'box_state', 'id', [
            'delete' => 'RESTRICT', 'update' => 'CASCADE',
        ])
            ->changeColumn('box_state_id', 'integer', ['signed' => false, 'null' => false, 'default' => 1])
            ->addForeignKey('box_state_id', 'box_state', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
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
