<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UpdateCmsFunctionItems extends AbstractMigration
{
    public function up(): void
    {
        $this->execute('UPDATE cms_functions SET title_en = "Account" WHERE id = 44');
        $this->execute('UPDATE cms_functions SET title_en = "Stock Planning" WHERE id = 160');
        $this->execute('UPDATE translate SET en = "Account", nl = "Rekening" WHERE id = 492');
    }

    public function down(): void
    {
        $this->execute('UPDATE cms_functions SET title_en = "Settings" WHERE id = 44');
        $this->execute('UPDATE cms_functions SET title_en = "Stock Overview" WHERE id = 160');
        $this->execute('UPDATE translate SET en = "Settings", nl = "Einstellungen" WHERE id = 492');
    }
}
