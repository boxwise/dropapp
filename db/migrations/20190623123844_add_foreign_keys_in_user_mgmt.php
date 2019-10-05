<?php

use Phinx\Migration\AbstractMigration;

class AddForeignKeysInUserMgmt extends AbstractMigration
{
    public function change()
    {
        $this->table('cms_users')
            ->changeColumn('cms_usergroups_id', 'integer', [
                'null' => true,
                'signed' => false,
            ])
            ->addForeignKey('cms_usergroups_id', 'cms_usergroups', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
        ;
        $this->table('cms_usergroups')
            ->changeColumn('organisation_id', 'integer', [
                'signed' => false,
            ])
            ->addForeignKey('organisation_id', 'organisations', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->changeColumn('userlevel', 'integer', [
                'signed' => false,
            ])
            ->addForeignKey('userlevel', 'cms_usergroups_levels', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
        ;
        $this->table('camps')
            ->changeColumn('organisation_id', 'integer', [
                'signed' => false,
            ])
            ->addForeignKey('organisation_id', 'organisations', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
        ;
        $this->table('cms_usergroups_camps')
            ->changeColumn('cms_usergroups_id', 'integer', [
                'signed' => false,
            ])
            ->addForeignKey('cms_usergroups_id', 'cms_usergroups', 'id', [
                'delete' => 'CASCADE', 'update' => 'CASCADE',
            ])
            ->changeColumn('camp_id', 'integer', [
                'signed' => false,
            ])
            ->addForeignKey('camp_id', 'camps', 'id', [
                'delete' => 'CASCADE', 'update' => 'CASCADE',
            ])
            ->save()
        ;
        $this->table('cms_usergroups_functions')
            ->changeColumn('cms_usergroups_id', 'integer', [
                'signed' => false,
            ])
            ->addForeignKey('cms_usergroups_id', 'cms_usergroups', 'id', [
                'delete' => 'CASCADE', 'update' => 'CASCADE',
            ])
            ->addForeignKey('cms_functions_id', 'cms_functions', 'id', [
                'delete' => 'CASCADE', 'update' => 'CASCADE',
            ])
            ->save()
        ;
        $this->table('cms_functions_camps')
            ->addForeignKey('cms_functions_id', 'cms_functions', 'id', [
                'delete' => 'CASCADE', 'update' => 'CASCADE',
            ])
            ->changeColumn('camps_id', 'integer', [
                'signed' => false,
            ])
            ->addForeignKey('camps_id', 'camps', 'id', [
                'delete' => 'CASCADE', 'update' => 'CASCADE',
            ])
            ->save()
        ;
    }
}
