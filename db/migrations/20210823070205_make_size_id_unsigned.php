<?php

use Phinx\Migration\AbstractMigration;

class MakeSizeIdUnsigned extends AbstractMigration
{
    public function up(): void
    {
        $this->table('itemsout')
            ->dropForeignKey('size_id')
            ->save()
        ;

        $this->table('stock')
            ->dropForeignKey('size_id')
            ->save()
        ;

        $this->table('transactions')
            ->dropForeignKey('size_id')
            ->removeColumn('size_id')
            ->save()
        ;

        $this->table('sizes')
            ->changeColumn('id', 'integer', ['identity' => true, 'signed' => false])
            ->save()
        ;

        $this->table('itemsout')
            ->changeColumn('size_id', 'integer', ['signed' => false, 'null' => true])
            ->addForeignKey('size_id', 'sizes', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        $this->table('stock')
            ->changeColumn('size_id', 'integer', ['signed' => false, 'null' => false])
            ->addForeignKey('size_id', 'sizes', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
        ;
    }

    /**
     * Migrate Down.
     */
    public function down() {}
}
