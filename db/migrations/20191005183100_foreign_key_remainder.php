<?php

use Phinx\Migration\AbstractMigration;

class ForeignKeyRemainder extends AbstractMigration
{
    public function change()
    {
        $this->table('stock')
            ->changeColumn('size_id', 'integer', [
                'null' => true,
            ])
            ->changeColumn('product_id', 'integer', [
                'null' => true,
            ])
            ->save()
        ;
        $this->execute('UPDATE stock SET size_id = null where size_id = 0');
        $this->execute('UPDATE stock SET product_id = null where product_id = 0');

        $this->table('stock')
            ->addForeignKey('size_id', 'sizes', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->addForeignKey('product_id', 'products', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
;

        $this->table('people')
            ->changeColumn('parent_id', 'integer', [
                'null' => true,
            ])
            ->save()
        ;
        $this->execute('UPDATE people SET parent_id = null where parent_id = 0');

        $this->table('people')
            ->addForeignKey('parent_id', 'people', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
;
    }
}
