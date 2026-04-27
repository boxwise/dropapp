<?php

use Phinx\Migration\AbstractMigration;

class UnassignDeletedTags extends AbstractMigration
{
    public function change(): void
    {
        $updated_rows = $this->execute('
            UPDATE tags_relations tr
            INNER JOIN tags t ON t.id = tr.tag_id
            SET tr.deleted_on = t.deleted, tr.deleted_by_id = 1
            WHERE tr.deleted_on IS NULL AND t.deleted > 0
        ');
        $this->output->writeln('Updated rows: '.$updated_rows);
    }
}
