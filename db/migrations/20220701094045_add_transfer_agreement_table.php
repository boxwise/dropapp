<?php

use Phinx\Migration\AbstractMigration;

class AddTransferAgreementTable extends AbstractMigration
{
    public function change(): void
    {
        $transferAgreement = $this->table('transfer_agreement', [
            'id' => true,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'signed' => false,
            'encoding' => 'utf8mb4',
            'collation' => 'utf8_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ]);

        $transferAgreement->addColumn('source_organisation_id', 'integer', [
            'null' => false,
            'signed' => false,
            'after' => 'id',
        ])
            ->addColumn('target_organisation_id', 'integer', [
                'null' => false,
                'signed' => false,
                'after' => 'source_organisation_id',
            ])
            ->addColumn('state', 'string', [
                'null' => false,
                'default' => 'UnderReview',
                'limit' => 255,
                'after' => 'target_organisation_id',
            ])
            ->addColumn('type', 'string', [
                'null' => false,
                'limit' => 255,
                'after' => 'state',
            ])
            ->addColumn('requested_on', 'datetime', [
                'null' => false,
                'after' => 'type',
            ])
            ->addColumn('requested_by', 'integer', [
                'null' => false,
                'signed' => false,
                'after' => 'requested_on',
            ])
            ->addColumn('accepted_on', 'datetime', [
                'null' => true,
                'after' => 'requested_by',
            ])
            ->addColumn('accepted_by', 'integer', [
                'null' => true,
                'signed' => false,
                'after' => 'accepted_on',
            ])
            ->addColumn('terminated_on', 'datetime', [
                'null' => true,
                'after' => 'accepted_by',
            ])
            ->addColumn('terminated_by', 'integer', [
                'null' => true,
                'signed' => false,
                'after' => 'terminated_on',
            ])
            ->addColumn('valid_from', 'datetime', [
                'null' => false,
                'after' => 'terminated_by',
            ])
            ->addColumn('valid_until', 'datetime', [
                'null' => true,
                'after' => 'valid_from',
            ])
            ->addColumn('comment', 'text', [
                'null' => true,
                'after' => 'transfer_agreement_id',
            ])
            ->addForeignKey('source_organisation_id', 'organisations', 'id', [
                'update' => 'CASCADE',
            ])
            ->addForeignKey('target_organisation_id', 'organisations', 'id', [
                'update' => 'CASCADE',
            ])
            ->addForeignKey('requested_by', 'cms_users', 'id', [
                'update' => 'CASCADE',
            ])
            ->addForeignKey('accepted_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->addForeignKey('terminated_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])

            ->addIndex(['source_organisation_id'], ['name' => 'transfer_agreement_source_organisation_id'])
            ->addIndex(['target_organisation_id'], ['name' => 'transfer_agreement_target_organisation_id'])
            ->addIndex(['requested_by'], ['name' => 'transfer_agreement_requested_by'])
            ->addIndex(['accepted_by'], ['name' => 'transfer_agreement_accepted_by'])
            ->addIndex(['terminated_by'], ['name' => 'transfer_agreement_terminated_by'])
            ->create()
        ;
    }
}
