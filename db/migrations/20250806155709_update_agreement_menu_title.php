<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UpdateAgreementMenuTitle extends AbstractMigration
{
    public function up(): void
    {
        $this->execute("
            UPDATE `cms_functions` SET `title_en` = 'Manage Network' WHERE `id` = '165';
        ");
    }

    public function down(): void
    {
        $this->execute("
            UPDATE `cms_functions` SET `title_en` = 'Manage Agreements' WHERE `id` = '165';
        ");
    }
}
