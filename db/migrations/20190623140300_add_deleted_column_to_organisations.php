<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class AddDeletedColumnToOrganisations extends AbstractMigration
{
    public function change(): void
    {
        $this->table('camps', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8',
            'collation' => 'utf8_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->changeColumn('organisation_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'after' => 'name',
            ])
            ->addIndex(['organisation_id'], [
                'name' => 'organisation_id',
                'unique' => false,
            ])
            ->save()
        ;
        $this->table('cms_functions_camps', [
            'id' => false,
            'engine' => 'InnoDB',
            'encoding' => 'utf8',
            'collation' => 'utf8_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->changeColumn('camps_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
            ])
            ->addIndex(['cms_functions_id'], [
                'name' => 'cms_functions_id',
                'unique' => false,
            ])
            ->addIndex(['camps_id'], [
                'name' => 'camps_id',
                'unique' => false,
            ])
            ->save()
        ;
        $this->table('cms_usergroups', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8',
            'collation' => 'utf8_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->changeColumn('organisation_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'after' => 'modified_by',
            ])
            ->changeColumn('userlevel', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'after' => 'organisation_id',
            ])
            ->addIndex(['organisation_id'], [
                'name' => 'organisation_id',
                'unique' => false,
            ])
            ->addIndex(['userlevel'], [
                'name' => 'userlevel',
                'unique' => false,
            ])
            ->save()
        ;
        $this->table('cms_usergroups_camps', [
            'id' => false,
            'engine' => 'InnoDB',
            'encoding' => 'utf8',
            'collation' => 'utf8_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->changeColumn('camp_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
            ])
            ->changeColumn('cms_usergroups_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'after' => 'camp_id',
            ])
            ->addIndex(['cms_usergroups_id'], [
                'name' => 'cms_usergroups_id',
                'unique' => false,
            ])
            ->addIndex(['camp_id'], [
                'name' => 'camp_id',
                'unique' => false,
            ])
            ->save()
        ;
        $this->table('cms_usergroups_functions', [
            'id' => false,
            'engine' => 'InnoDB',
            'encoding' => 'utf8',
            'collation' => 'utf8_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->changeColumn('cms_usergroups_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'after' => 'cms_functions_id',
            ])
            ->addIndex(['cms_usergroups_id'], [
                'name' => 'cms_usergroups_id',
                'unique' => false,
            ])
            ->addIndex(['cms_functions_id'], [
                'name' => 'cms_functions_id',
                'unique' => false,
            ])
            ->save()
        ;
        $this->table('cms_users', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8',
            'collation' => 'utf8_bin',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->changeColumn('cms_usergroups_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
            ])
            ->addIndex(['cms_usergroups_id'], [
                'name' => 'cms_usergroups_id',
                'unique' => false,
            ])
            ->save()
        ;
        $this->table('organisations', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8',
            'collation' => 'utf8_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('deleted', 'datetime', [
                'null' => true,
                'after' => 'created_by',
            ])
            ->save()
        ;
    }
}
