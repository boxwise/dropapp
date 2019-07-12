<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class AddDeletedColumnToUsergroups extends AbstractMigration
{
    public function change()
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
        ->changeColumn('shop', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'laundry_cyclestart',
            ])
        ->changeColumn('maxfooddrops_adult', 'integer', [
                'null' => true,
                'default' => '25',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'market',
            ])
        ->changeColumn('maxfooddrops_child', 'integer', [
                'null' => true,
                'default' => '25',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'maxfooddrops_adult',
            ])
        ->changeColumn('modified', 'datetime', [
                'null' => true,
                'after' => 'maxfooddrops_child',
            ])
        ->changeColumn('modified_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'modified',
            ])
        ->changeColumn('name', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'modified_by',
            ])
        ->changeColumn('organisation_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'name',
            ])
        ->changeColumn('schedulebreak', 'string', [
                'null' => false,
                'default' => '1',
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'organisation_id',
            ])
        ->changeColumn('schedulebreakduration', 'string', [
                'null' => false,
                'default' => '1',
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'schedulebreak',
            ])
        ->changeColumn('schedulebreakstart', 'string', [
                'null' => false,
                'default' => '13:00',
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'schedulebreakduration',
            ])
        ->changeColumn('schedulestart', 'string', [
                'null' => false,
                'default' => '11:00',
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'schedulebreakstart',
            ])
        ->changeColumn('schedulestop', 'string', [
                'null' => false,
                'default' => '17:00',
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'schedulestart',
            ])
        ->changeColumn('scheduletimeslot', 'string', [
                'null' => false,
                'default' => '0.5',
                'limit' => 255,
                'collation' => 'latin1_swedish_ci',
                'encoding' => 'latin1',
                'after' => 'schedulestop',
            ])
        ->changeColumn('seq', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'scheduletimeslot',
            ])
        ->changeColumn('workshop', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'seq',
            ])
            ->save();
        $this->table('cms_usergroups', [
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
                'after' => 'userlevel',
            ])
            ->save();
        $this->table('cms_users', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8',
                'collation' => 'utf8_bin',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
        ->changeColumn('organisation_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'after' => 'naam',
            ])
        ->changeColumn('email', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'organisation_id',
            ])
        ->changeColumn('is_admin', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'email',
            ])
        ->changeColumn('lastlogin', 'datetime', [
                'null' => false,
                'after' => 'is_admin',
            ])
        ->changeColumn('lastaction', 'datetime', [
                'null' => false,
                'after' => 'lastlogin',
            ])
        ->changeColumn('created', 'datetime', [
                'null' => true,
                'after' => 'lastaction',
            ])
        ->changeColumn('created_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'created',
            ])
        ->changeColumn('modified', 'datetime', [
                'null' => true,
                'after' => 'created_by',
            ])
        ->changeColumn('modified_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'modified',
            ])
        ->changeColumn('resetpassword', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8_bin',
                'encoding' => 'utf8',
                'after' => 'modified_by',
            ])
        ->changeColumn('language', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'resetpassword',
            ])
        ->changeColumn('deleted', 'datetime', [
                'null' => false,
                'after' => 'language',
            ])
        ->changeColumn('cms_usergroups_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'deleted',
            ])
        ->changeColumn('valid_firstday', 'date', [
                'null' => true,
                'after' => 'cms_usergroups_id',
            ])
        ->changeColumn('valid_lastday', 'date', [
                'null' => true,
                'after' => 'valid_firstday',
            ])
            ->save();
    }
}
