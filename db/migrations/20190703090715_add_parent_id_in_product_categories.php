<?php

use Phinx\Migration\AbstractMigration;

class AddParentIdInProductCategories extends AbstractMigration
{
    public function change(): void
    {
        $this->table('product_categories')
            ->addColumn('parent_id', 'integer', [
                'signed' => false,
                'null' => true,
                'default' => null,
            ])
            ->save()
        ;
    }
}
