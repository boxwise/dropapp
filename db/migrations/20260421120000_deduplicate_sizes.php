<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Removes duplicate size labels from the sizes table (keeping the entry with the
 * smallest ID for each label).  All references in the stock, shipment_detail and
 * history tables are updated to point at the surviving size ID before the surplus
 * rows are deleted.
 *
 * Removed ID → kept ID mapping (grouped by label):
 *   S            53 → 1          M            54 → 2          L            55 → 3
 *   34           67 → 28        35           65 → 29
 *   39          57,186 → 33    40          58,187 → 34    41          59,188 → 35
 *   Mixed      70,71,204-223 → 52
 *   All ages   103,123,140,148,163 → 97
 *   7-12 mo     120 → 47       13-18 mo    121 → 48       19-24 mo    122 → 44
 *   6-10 yr     124 → 117      11-15 yr    125 → 118
 *   2-5 yr      130 → 116      0-6 mo      138 → 119
 *   2-3 yr      141 → 10       4-5 yr      142 → 11
 *   24          171 → 18       25          172 → 19       26          173 → 20
 *   27          174 → 21       28          175 → 22       29          176 → 23
 *   30          177 → 24       31          178 → 25       32          179 → 26
 *   33          180 → 27       34 (dup)    181 → 28       35 (dup)    182 → 29
 *   36          183 → 30       37          184 → 31       38          185 → 32
 *   39 (dup)    186 → 33       40 (dup)    187 → 34       41 (dup)    188 → 35
 *   42          189 → 60       43          190 → 61       44          191 → 62
 *   45          192 → 63
 */
final class DeduplicateSizes extends AbstractMigration
{
    private const REMOVED_IDS = '53,54,55,57,58,59,65,67,70,71,103,120,121,122,123,124,125,'
        .'130,138,140,141,142,148,163,171,172,173,174,175,176,177,178,179,180,181,182,183,'
        .'184,185,186,187,188,189,190,191,192,204,205,206,207,208,209,210,211,212,213,214,'
        .'215,216,217,218,219,220,221,222,223';

    /** SQL CASE expression mapping removed size IDs to their canonical replacement. */
    private const REMAP_CASE = 'CASE size_id
        WHEN  53 THEN   1  WHEN  54 THEN   2  WHEN  55 THEN   3
        WHEN  57 THEN  33  WHEN  58 THEN  34  WHEN  59 THEN  35
        WHEN  65 THEN  29  WHEN  67 THEN  28
        WHEN  70 THEN  52  WHEN  71 THEN  52
        WHEN 103 THEN  97  WHEN 123 THEN  97  WHEN 140 THEN  97
        WHEN 148 THEN  97  WHEN 163 THEN  97
        WHEN 120 THEN  47  WHEN 121 THEN  48  WHEN 122 THEN  44
        WHEN 124 THEN 117  WHEN 125 THEN 118
        WHEN 130 THEN 116  WHEN 138 THEN 119
        WHEN 141 THEN  10  WHEN 142 THEN  11
        WHEN 171 THEN  18  WHEN 172 THEN  19  WHEN 173 THEN  20
        WHEN 174 THEN  21  WHEN 175 THEN  22  WHEN 176 THEN  23
        WHEN 177 THEN  24  WHEN 178 THEN  25  WHEN 179 THEN  26
        WHEN 180 THEN  27  WHEN 181 THEN  28  WHEN 182 THEN  29
        WHEN 183 THEN  30  WHEN 184 THEN  31  WHEN 185 THEN  32
        WHEN 186 THEN  33  WHEN 187 THEN  34  WHEN 188 THEN  35
        WHEN 189 THEN  60  WHEN 190 THEN  61  WHEN 191 THEN  62
        WHEN 192 THEN  63
        WHEN 204 THEN  52  WHEN 205 THEN  52  WHEN 206 THEN  52
        WHEN 207 THEN  52  WHEN 208 THEN  52  WHEN 209 THEN  52
        WHEN 210 THEN  52  WHEN 211 THEN  52  WHEN 212 THEN  52
        WHEN 213 THEN  52  WHEN 214 THEN  52  WHEN 215 THEN  52
        WHEN 216 THEN  52  WHEN 217 THEN  52  WHEN 218 THEN  52
        WHEN 219 THEN  52  WHEN 220 THEN  52  WHEN 221 THEN  52
        WHEN 222 THEN  52  WHEN 223 THEN  52
        ELSE size_id
    END';

    public function up(): void
    {
        $ids = self::REMOVED_IDS;
        $case = self::REMAP_CASE;

        // --- stock -------------------------------------------------------
        $this->execute("UPDATE stock
            SET size_id = {$case}
            WHERE size_id IN ({$ids})");

        // --- shipment_detail ---------------------------------------------
        $sourceCaseExpr = str_replace('size_id', 'source_size_id', $case);
        $targetCaseExpr = str_replace('size_id', 'target_size_id', $case);

        $this->execute("UPDATE shipment_detail
            SET source_size_id = {$sourceCaseExpr}
            WHERE source_size_id IN ({$ids})");

        $this->execute("UPDATE shipment_detail
            SET target_size_id = {$targetCaseExpr}
            WHERE target_size_id IN ({$ids})");

        // --- history (size_id changes on stock rows) ---------------------
        $fromCaseExpr = str_replace('size_id', 'from_int', $case);
        $toCaseExpr = str_replace('size_id', 'to_int', $case);

        $this->execute("UPDATE history
            SET from_int = {$fromCaseExpr}
            WHERE tablename = 'stock' AND changes = 'size_id'
              AND from_int IN ({$ids})");

        $this->execute("UPDATE history
            SET to_int = {$toCaseExpr}
            WHERE tablename = 'stock' AND changes = 'size_id'
              AND to_int IN ({$ids})");

        // --- delete duplicate sizes --------------------------------------
        $this->execute("DELETE FROM sizes WHERE id IN ({$ids})");
    }

    public function down(): void
    {
        // This migration is not reversible: the duplicate size rows and their
        // original foreign-key references cannot be reliably reconstructed.
    }
}
