<?php

use Phinx\Migration\AbstractMigration;

class ResetChangedBoxLabelIdentifiers extends AbstractMigration
{
    public function up(): void
    {
        $updated_box_id_rows = $this->execute('
-- MAIN QUERY to reset box label identifiers to original.
-- Should update 167 rows
UPDATE
    stock AS s
SET
    box_id = (

    -- you can execute the following subquery on itself to get the original label identifiers
    SELECT
        -- box_id,
        original_label_identifier
    FROM
        (
        -- Find original label identifier
        select
            hhh.box_id,
            h.changes,
            substring_index(substring_index(h.changes, \'"\', 2), \'"\', -1) as original_label_identifier
        from
            history h
        inner join (
            -- For each box, find the ID of oldest change
            select
                min(hh.id) as oldest_change_id,
                record_id as box_id
            from
                history hh
            where
                tablename = "stock"
                and changes like "box_id changed from %"
                and record_id <> 51103
            group by
                record_id
            order by
                record_id
        ) as hhh
        on
            h.id = hhh.oldest_change_id
    ) AS subquery

where
    -- correlates the subquery with the outer UPDATE statement, ensuring that only the relevant rows are updated
    subquery.box_id = s.id
)
WHERE
    s.id IN (
    SELECT
        DISTINCT record_id
    FROM
        history
    WHERE
        tablename = "stock"
        and changes like "box_id changed from %"
        and record_id <> 51103
);
        ');
        $this->output->writeln('Updated box_id rows: '.$updated_box_id_rows);

        $deleted_history_entries = $this->execute('
-- Delete all annoying change records
-- Should update 299 rows
DELETE
FROM
    history
WHERE
    tablename = "stock"
    and changes like "box_id changed from %"
    and record_id <> 51103;
        ');
        $this->output->writeln('Deleted history entries: '.$deleted_history_entries);
    }

    public function down() {}
}
