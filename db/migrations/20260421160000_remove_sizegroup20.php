<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Removes sizegroup 20 ("Children by month (2-5)"), which is superseded by
 * sizegroup 17 ("Children by year (2-5, 6-10, 11-15)"):
 *
 * 1. Reassigns any products that reference sizegroup 20 to sizegroup 17.
 * 2. Deletes the sizes_sizegroup rows for sizegroup 20.
 * 3. Deletes sizegroup ID 20.
 *
 * The down() method restores the sizegroup row and its sizes_sizegroup entries,
 * but does not restore the product reassignments (partial rollback).
 */
final class RemoveSizegroup20 extends AbstractMigration
{
    public function up(): void
    {
        // --- products -------------------------------------------------------
        $updated_products_rows = $this->execute('UPDATE `products`
            SET `sizegroup_id` = 17
            WHERE `sizegroup_id` = 20');
        $this->output->writeln('Updated products rows: '.$updated_products_rows.' . Expected: 29 (0)');

        // --- sizes_sizegroup ------------------------------------------------
        $deleted_rows = $this->execute('DELETE FROM `sizes_sizegroup`
            WHERE `sizegroup_id` = 20');
        $this->output->writeln('Deleted sizes_sizegroup rows: '.$deleted_rows.' . Expected: 2');

        // --- sizegroup ------------------------------------------------------
        $this->execute('DELETE FROM `sizegroup`
            WHERE `id` = 20');
    }

    public function down(): void
    {
        // --- sizegroup ------------------------------------------------------
        $this->execute("INSERT INTO `sizegroup` (`id`, `label`) VALUES
            (20, 'Children by month (2-5)')");

        // --- sizes_sizegroup ------------------------------------------------
        // Restore the two size memberships that existed before up() ran.
        $this->execute('INSERT INTO `sizes_sizegroup` (`size_id`, `sizegroup_id`, `seq`) VALUES
            (116, 20, 30),
            (52,  20, 999)');

        // Note: products that were reassigned to sizegroup 17 are NOT restored.
    }
}
