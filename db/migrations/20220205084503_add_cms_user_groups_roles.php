<?php

use Phinx\Migration\AbstractMigration;

class AddCmsUserGroupsRoles extends AbstractMigration
{
    public function up()
    {
        // create Usergroup Roles table
        $userGroupsRoles = $this->table('cms_usergroups_roles', ['id' => false]);

        $userGroupsRoles->addColumn('cms_usergroups_id', 'integer', ['signed' => false, 'null' => false])
            ->addColumn('auth0_role_id', 'string', ['null' => false])
            ->addColumn('auth0_role_name', 'string', [
                'null' => false,
                'limit' => 255,
            ])
            ->addIndex(['cms_usergroups_id'], ['unique' => false])
            ->addIndex(['cms_usergroups_id', 'auth0_role_id'], ['unique' => true])
            ->addForeignKey('cms_usergroups_id', 'cms_usergroups', 'id', [
                'delete' => 'CASCADE', 'update' => 'CASCADE',
            ])->save();
    }

    public function down()
    {
        $table = $this->table('cms_usergroups_roles');
        $table->removeIndex(['cms_usergroups_id'])
            ->removeIndex(['cms_usergroups_id', 'auth0_role_id'])
            ->dropForeignKey('cms_usergroups_id')
            ->save()
        ;
        $table->drop('cms_usergroups_roles')->save();
    }
}
