<?php

use Phinx\Migration\AbstractMigration;

class AddActionPermissionsToCmsFunctions extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->table('cms_functions')
            ->addColumn('action_permissions', 'string', [
                'null' => true,
                'default' => null,
                'limit' => '255',
            ])->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        // $this->execute("SHOW COLUMNS FROM cms_functions like 'action_permissions'");
        $this->table('cms_functions')
            ->removeColumn('action_permissions')->update();
    }
}
