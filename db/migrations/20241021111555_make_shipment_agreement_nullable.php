<?php

use Phinx\Migration\AbstractMigration;

class MakeShipmentAgreementNullable extends AbstractMigration
{
    public function up(): void
    {
        $shipment = $this->table('shipment');
        $shipment->changeColumn('transfer_agreement_id', 'integer', ['null' => true, 'signed' => false])
            ->update()
        ;
    }

    public function down(): void
    {
        $shipment = $this->table('shipment');
        $shipment->dropForeignKey('transfer_agreement_id')
            ->update()
        ;
        $shipment->changeColumn('transfer_agreement_id', 'integer', ['null' => false, 'signed' => false])
            ->addForeignKey('transfer_agreement_id', 'transfer_agreement', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->update()
        ;
    }
}
