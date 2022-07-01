<?php

use Phinx\Migration\AbstractMigration;

class AddTransferAgreementDetailTable extends AbstractMigration
{
    public function change()
    {
        $transferAgreementDetail = $this->table('transfer_agreement_detail', [
            'id' => true,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'signed' => false,
            'encoding' => 'utf8mb4',
            'collation' => 'utf8_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ]);

        $transferAgreementDetail->addColumn('transfer_agreement_id', 'integer', [
            'null' => false,
            'signed' => false,
            'after' => 'id',
        ])
            ->addColumn('source_base_id', 'integer', [
                'null' => true,
                'signed' => false,
                'after' => 'transfer_agreement_id',
            ])
            ->addColumn('target_base_id', 'integer', [
                'null' => true,
                'signed' => false,
                'after' => 'source_base_id',
            ])

            ->addForeignKey('transfer_agreement_id', 'transfer_agreement', 'id', [
                'update' => 'CASCADE',
            ])
            ->addForeignKey('source_base_id', 'camps', 'id', [
                'update' => 'CASCADE',
            ])
            ->addForeignKey('target_base_id', 'camps', 'id', [
                'update' => 'CASCADE',
            ])

            ->addIndex(['transfer_agreement_id'], ['name' => 'transfer_agreement_detail_transfer_agreement_id'])
            ->addIndex(['source_base_id'], ['name' => 'transfer_agreement_detail_source_base_id'])
            ->addIndex(['target_base_id'], ['name' => 'transfer_agreement_detail_target_base_id'])

            ->create()
        ;
    }
}
