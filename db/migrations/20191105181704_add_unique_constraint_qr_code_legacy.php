<?php

use Phinx\Migration\AbstractMigration;

class AddUniqueConstraintQrCodeLegacy extends AbstractMigration
{
    public function change(): void
    {
        $this->table('qr')
            ->addIndex(['code', 'legacy'], ['name' => 'code_legacy_unique', 'unique' => true])
            ->save()
        ;
    }
}
