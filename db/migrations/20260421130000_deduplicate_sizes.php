<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Removes duplicate size labels from the sizes table (keeping the entry with the
 * smallest ID for each label).  The preceding CreateSizesSizegroupTable migration
 * has already created sizes_sizegroup and populated it with one row per size, so
 * this migration remaps the sizes_sizegroup rows for duplicate sizes to their
 * canonical ID (preserving sizegroup memberships), then deletes the duplicate sizes.
 *
 * All references in stock, shipment_detail, history, itemsout and distro_events_*
 * tables are updated to point at the surviving size ID before the surplus rows
 * are deleted.
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

        // --- itemsout ----------------------------------------------------
        if ($this->hasTable('itemsout')) {
            $this->execute("UPDATE itemsout
                SET size_id = {$case}
                WHERE size_id IN ({$ids})");
        }

        // --- distro_events_* tables with size_id -------------------------
        foreach (['distro_events_packing_list_entries', 'distro_events_tracking_logs', 'distro_events_unboxed_item_collections'] as $dTable) {
            if ($this->hasTable($dTable)) {
                $this->execute("UPDATE `{$dTable}`
                    SET size_id = {$case}
                    WHERE size_id IN ({$ids})");
            }
        }

        // --- sizes_sizegroup ---------------------------------------------
        // Remap cross-reference rows: canonical sizes inherit all sizegroup
        // memberships from their duplicates.  INSERT IGNORE handles the rare
        // case where the canonical already belongs to the same sizegroup.
        // The ON DELETE CASCADE on fk_ss_size_id then removes the old rows
        // for the duplicate size IDs when they are deleted below.
        $ssCaseExpr = str_replace('size_id', 'ss.size_id', $case);
        $this->execute("INSERT IGNORE INTO `sizes_sizegroup` (`size_id`, `sizegroup_id`, `seq`)
            SELECT {$ssCaseExpr}, ss.`sizegroup_id`, ss.`seq`
            FROM `sizes_sizegroup` ss
            WHERE ss.`size_id` IN ({$ids})");

        // --- delete duplicate sizes (CASCADE removes their sizes_sizegroup rows) ---
        $this->execute("DELETE FROM sizes WHERE id IN ({$ids})");
    }

    public function down(): void
    {
        // Re-insert the duplicate size rows that were removed by up().
        // Note: sizegroup_id and seq columns no longer exist on sizes (they were
        // dropped by the preceding CreateSizesSizegroupTable migration), so only
        // id and label are restored here.  Foreign-key references in stock etc.
        // that were remapped cannot be reconstructed.
        $this->execute("INSERT IGNORE INTO sizes (id, label) VALUES
            (53,'S'),
            (54,'M'),
            (55,'L'),
            (57,'39'),
            (58,'40'),
            (59,'41'),
            (65,'35'),
            (67,'34'),
            (70,'Mixed'),
            (71,'Mixed'),
            (103,'All ages'),
            (120,'7-12 months'),
            (121,'13-18 months'),
            (122,'19-24 months'),
            (123,'All ages'),
            (124,'6-10 years'),
            (125,'11-15 years'),
            (130,'2-5 years'),
            (138,'0-6 months'),
            (140,'All ages'),
            (141,'2-3 years'),
            (142,'4-5 years'),
            (148,'All ages'),
            (163,'All ages'),
            (171,'24'),
            (172,'25'),
            (173,'26'),
            (174,'27'),
            (175,'28'),
            (176,'29'),
            (177,'30'),
            (178,'31'),
            (179,'32'),
            (180,'33'),
            (181,'34'),
            (182,'35'),
            (183,'36'),
            (184,'37'),
            (185,'38'),
            (186,'39'),
            (187,'40'),
            (188,'41'),
            (189,'42'),
            (190,'43'),
            (191,'44'),
            (192,'45'),
            (204,'Mixed'),
            (205,'Mixed'),
            (206,'Mixed'),
            (207,'Mixed'),
            (208,'Mixed'),
            (209,'Mixed'),
            (210,'Mixed'),
            (211,'Mixed'),
            (212,'Mixed'),
            (213,'Mixed'),
            (214,'Mixed'),
            (215,'Mixed'),
            (216,'Mixed'),
            (217,'Mixed'),
            (218,'Mixed'),
            (219,'Mixed'),
            (220,'Mixed'),
            (221,'Mixed'),
            (222,'Mixed'),
            (223,'Mixed')");

        // Re-insert the sizes_sizegroup rows for the restored sizes
        // (one row per size, matching the original sizes.sizegroup_id / sizes.seq values
        // from init.sql that were inserted by CreateSizesSizegroupTable)
        $this->execute('INSERT IGNORE INTO sizes_sizegroup (size_id, sizegroup_id, seq) VALUES
            (53, 5, 50),
            (54, 5, 51),
            (55, 5, 52),
            (57, 8, 81),
            (58, 8, 82),
            (59, 8, 83),
            (65, 9, 118),
            (67, 9, 117),
            (70, 5, 53),
            (71, 1, 6),
            (103, 4, 205),
            (120, 2, 41),
            (121, 2, 42),
            (122, 2, 43),
            (123, 2, 44),
            (124, 18, 20),
            (125, 18, 21),
            (130, 20, 30),
            (138, 22, 180),
            (140, 4, 182),
            (141, 23, 200),
            (142, 23, 201),
            (148, 23, 204),
            (163, 24, 234),
            (171, 26, 251),
            (172, 26, 252),
            (173, 26, 253),
            (174, 26, 254),
            (175, 26, 255),
            (176, 26, 256),
            (177, 26, 257),
            (178, 26, 258),
            (179, 26, 259),
            (180, 26, 260),
            (181, 26, 261),
            (182, 26, 262),
            (183, 26, 263),
            (184, 26, 264),
            (185, 26, 265),
            (186, 26, 266),
            (187, 26, 267),
            (188, 26, 268),
            (189, 26, 269),
            (190, 26, 270),
            (191, 26, 271),
            (192, 26, 272),
            (204, 2, 999),
            (205, 3, 999),
            (206, 4, 999),
            (207, 7, 999),
            (208, 8, 999),
            (209, 9, 999),
            (210, 12, 999),
            (211, 13, 999),
            (212, 16, 999),
            (213, 17, 999),
            (214, 18, 999),
            (215, 19, 999),
            (216, 20, 999),
            (217, 21, 999),
            (218, 22, 999),
            (219, 23, 999),
            (220, 24, 999),
            (221, 25, 999),
            (222, 26, 999),
            (223, 27, 999)');

        // Remove the sizegroup memberships that canonical sizes inherited
        // from their duplicates during up().  Each pair below is
        // (canonical_size_id, sizegroup_id_of_the_duplicate).
        $this->execute('DELETE FROM `sizes_sizegroup`
            WHERE (`size_id`, `sizegroup_id`) IN (
                ( 1,  5), ( 2,  5), ( 3,  5),
                (33,  8), (34,  8), (35,  8),
                (28,  9), (29,  9),
                (52,  1), (52,  5),
                (97,  2), (97,  4), (97, 23), (97, 24),
                (44,  2), (47,  2), (48,  2),
                (117, 18), (118, 18),
                (116, 20),
                (119, 22),
                (10, 23), (11, 23),
                (18, 26), (19, 26), (20, 26), (21, 26), (22, 26), (23, 26),
                (24, 26), (25, 26), (26, 26), (27, 26),
                (28, 26), (29, 26), (30, 26), (31, 26), (32, 26),
                (33, 26), (34, 26), (35, 26),
                (60, 26), (61, 26), (62, 26), (63, 26),
                (52,  2), (52,  3), (52,  4), (52,  7), (52,  8), (52,  9),
                (52, 12), (52, 13), (52, 16), (52, 17), (52, 18), (52, 19),
                (52, 20), (52, 21), (52, 22), (52, 23), (52, 24), (52, 25),
                (52, 26), (52, 27)
            )');
    }
}
