<?php

use Phinx\Migration\AbstractMigration;

class AddUniqueConstraintQrCodeLegacy extends AbstractMigration
{
    public function up()
    {
        $this->execute('ALTER TABLE qr ADD CONSTRAINT code_legacy_unique UNIQUE (code, legacy);');
    }
}
