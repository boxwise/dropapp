<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Creates the sizes_sizegroup cross-reference table that stores the many-to-many
 * relationship between sizes and sizegroups, then drops the now-redundant
 * sizegroup_id and seq columns from the sizes table.
 *
 * This migration runs BEFORE DeduplicateSizes so that every size – including
 * the duplicates that will later be removed – already has a row in
 * sizes_sizegroup before deduplication happens. When DeduplicateSizes removes
 * duplicate rows from sizes, the corresponding sizes_sizegroup rows are removed
 * automatically via the size_id foreign key's ON DELETE CASCADE behavior.
 */
final class CreateSizesSizegroupTable extends AbstractMigration
{
    public function up(): void
    {
        // ----------------------------------------------------------------
        // 1. Create cross-reference table
        // ----------------------------------------------------------------
        $this->execute('CREATE TABLE `sizes_sizegroup` (
            `size_id`      INT UNSIGNED NOT NULL,
            `sizegroup_id` INT UNSIGNED NOT NULL,
            `seq`          INT          DEFAULT NULL,
            PRIMARY KEY (`size_id`, `sizegroup_id`),
            KEY `idx_sizegroup_id` (`sizegroup_id`),
            CONSTRAINT `fk_ss_size_id`
                FOREIGN KEY (`size_id`)      REFERENCES `sizes`     (`id`)
                ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT `fk_ss_sizegroup_id`
                FOREIGN KEY (`sizegroup_id`) REFERENCES `sizegroup` (`id`)
                ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC');

        // ----------------------------------------------------------------
        // 2. Populate from existing sizes.sizegroup_id (one row per size,
        //    including rows for the duplicates that DeduplicateSizes will
        //    later remove along with their sizes_sizegroup entries)
        // ----------------------------------------------------------------
        $this->execute('INSERT INTO `sizes_sizegroup` (`size_id`, `sizegroup_id`, `seq`)
            SELECT `id`, `sizegroup_id`, `seq`
            FROM   `sizes`
            WHERE  `sizegroup_id` IS NOT NULL');

        // ----------------------------------------------------------------
        // 3. Drop the now-redundant columns from sizes
        //    (must drop the FK constraint and its index first)
        // ----------------------------------------------------------------
        $this->execute('ALTER TABLE `sizes`
            DROP FOREIGN KEY `sizes_ibfk_4`,
            DROP INDEX       `sizegroup_id`,
            DROP COLUMN      `sizegroup_id`,
            DROP COLUMN      `seq`');
    }

    public function down(): void
    {
        // Restore sizegroup_id and seq columns on sizes
        $this->execute('ALTER TABLE `sizes`
            ADD COLUMN `sizegroup_id` INT UNSIGNED DEFAULT NULL AFTER `label`,
            ADD COLUMN `seq`          INT          DEFAULT NULL AFTER `sizegroup_id`,
            ADD KEY    `sizegroup_id` (`sizegroup_id`),
            ADD CONSTRAINT `sizes_ibfk_4`
                FOREIGN KEY (`sizegroup_id`) REFERENCES `sizegroup` (`id`)
                ON UPDATE CASCADE');

        // Re-populate from the cross-reference table.
        // Where a size belongs to multiple sizegroups, pick the row with the
        // smallest sizegroup_id as the "primary" one (mirrors the original data).
        $this->execute('UPDATE `sizes` s
            JOIN (
                SELECT   size_id, sizegroup_id, seq
                FROM     sizes_sizegroup ss1
                WHERE    sizegroup_id = (
                    SELECT MIN(sizegroup_id)
                    FROM   sizes_sizegroup ss2
                    WHERE  ss2.size_id = ss1.size_id
                )
            ) best ON best.size_id = s.id
            SET s.sizegroup_id = best.sizegroup_id,
                s.seq          = best.seq');

        $this->execute('DROP TABLE `sizes_sizegroup`');
    }
}
