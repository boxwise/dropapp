<?php

use Phinx\Migration\AbstractMigration;

class CreateComposteIndexOnHistoryTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('history')
            ->addIndex(['record_id', 'changedate'], [
                'name' => 'recordid_changedate_composite_index',
                'unique' => false,
            ])
            ->save()
        ;
        $this->table('history')
            ->addIndex(['tablename', 'record_id', 'changedate'], [
                'name' => 'tablename_recordid_changedate_composite_index',
                'unique' => false,
            ])
            ->save()
        ;
    }
}
