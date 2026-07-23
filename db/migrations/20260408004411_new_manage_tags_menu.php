<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class NewManageTagsMenu extends AbstractMigration
{
    public function up(): void
    {
        $this->execute("
            UPDATE `cms_functions` SET `title_en` = 'Manage Tags v2', `include` = 'new_manage_tags' WHERE `id` = '162';
        ");
    }

    public function down(): void
    {
        $this->execute("
            UPDATE `cms_functions` SET `title_en` = 'Manage Tags', `include` = 'tags' WHERE `id` = '162';
        ");
    }
}
