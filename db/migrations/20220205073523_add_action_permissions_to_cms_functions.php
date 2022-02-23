<?php

use Phinx\Migration\AbstractMigration;

class AddActionPermissionsToCmsFunctions extends AbstractMigration
{
    public function change()
    {
        $this->table('cms_functions')
            ->addColumn('action_permissions', 'string', [
                'null' => true,
                'default' => null,
                'limit' => '255',
            ])->save();
    }
}
