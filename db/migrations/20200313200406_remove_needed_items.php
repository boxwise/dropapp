<?php

use Phinx\Migration\AbstractMigration;

class RemoveNeededItems extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up(): void
    {
        // delete need_periods table
        $this->execute('DROP TABLE need_periods');

        // delete general stock
        $this->execute('DELETE FROM cms_functions WHERE id=129');

        // delete needed items menu
        $this->execute('DELETE FROM cms_functions_camps WHERE cms_functions_id=117');
        $this->execute('DELETE FROM cms_usergroups_functions WHERE cms_functions_id=117');
        $this->execute('DELETE FROM cms_functions WHERE id=117');
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
    }
}
