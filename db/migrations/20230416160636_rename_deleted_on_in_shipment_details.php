<?php

use Phinx\Migration\AbstractMigration;

class RenameDeletedOnInShipmentDetails extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('shipment_detail');
        $hasdeletedon = $table->hasColumn('deleted_on');
        $hasdeletedbyid = $table->hasColumn('deleted_on');

        if ($hasdeletedon) {
            $table
                ->renameColumn('deleted_on', 'removed_on')
                ->update()
            ;
        }
        if ($hasdeletedbyid) {
            $table
                ->renameColumn('deleted_by_id', 'removed_by_id')
                ->update()
            ;
        }
    }

    public function down(): void
    {
        $table = $this->table('shipment_detail');
        $table
            ->renameColumn('removed_on', 'deleted_on')
            ->renameColumn('removed_by_id', 'deleted_by_id')
            ->update()
        ;
    }
}
