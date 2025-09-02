<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class RemoveClassicManageBoxesMenu extends AbstractMigration
{
    public function up(): void
    {
        $this->execute("
            UPDATE `cms_functions` SET `visible` = 0 WHERE `id` = '90';
        ");
        $this->execute("
            UPDATE `cms_functions` SET `title_en` = 'Manage Boxes v2' WHERE `id` = '166';
        ");
        $this->execute("
            UPDATE `cms_functions` SET `title_en` = 'Manage Products v2' WHERE `id` = '67';
        ");
    }

    public function down(): void
    {
        $this->execute("
            UPDATE `cms_functions` SET `visible` = 1 WHERE `id` = '90';
        ");
        $this->execute("
            UPDATE `cms_functions` SET `title_en` = 'Manage Boxes v2 (<span>beta</span>)' WHERE `id` = '166';
        ");
        $this->execute("
            UPDATE `cms_functions` SET `title_en` = 'Manage Products' WHERE `id` = '67';
        ");
    }
}
