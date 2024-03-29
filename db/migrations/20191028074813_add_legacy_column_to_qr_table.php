<?php

use Phinx\Migration\AbstractMigration;

class AddLegacyColumnToQrTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('qr')
            ->addColumn('legacy', 'integer', ['default' => 0, 'null' => false])
            ->save()
        ;
    }
}
