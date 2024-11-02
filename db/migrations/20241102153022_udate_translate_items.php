<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UdateTranslateItems extends AbstractMigration
{
    public function up(): void
    {
        $this->execute('UPDATE translate SET en = "Account", nl = "Rekening" WHERE id = 492');
    }

    public function down(): void
    {
        $this->execute('UPDATE translate SET en = "Settings", nl = "Einstellungen" WHERE id = 492');
    }
}
