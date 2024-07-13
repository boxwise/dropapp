<?php

use Phinx\Migration\AbstractMigration;

class DeleteDuplicateBoxDeletions extends AbstractMigration
{
    public function up(): void
    {
        // Delete duplicate "Record deleted" history entries for boxes in camp 43.
        // In total 1281 entries are duplicates.
        $deleted_rows = $this->execute('
DELETE FROM history
WHERE id IN (
    SELECT MAX(h.id) as max_id, h.record_id
    FROM history h
    INNER JOIN stock s
    ON s.id = h.record_id
    AND h.tablename = "stock"
    AND (DATE(h.changedate) = "2023-01-12" OR DATE(h.changedate) = "2023-01-11")
    AND h.changes = "Record deleted"
    INNER JOIN locations l
    ON l.id = s.location_id AND l.camp_id = 43
    GROUP BY h.record_id
);
        ');
        $this->output->writeln('Deleted box_id rows: '.$deleted_rows);
    }

    public function down() {}
}
