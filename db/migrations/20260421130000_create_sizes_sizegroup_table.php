<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Creates the sizes_sizegroup cross-reference table that stores the many-to-many
 * relationship between sizes and sizegroups, then drops the now-redundant
 * sizegroup_id and seq columns from the sizes table.
 *
 * The cross-reference is populated from:
 *   1. The existing sizes.sizegroup_id / sizes.seq values (one entry per size).
 *   2. Additional entries for sizes that absorbed duplicates in the preceding
 *      DeduplicateSizes migration – those duplicates each belonged to a different
 *      sizegroup, so the surviving size must be associated with all of them.
 *
 * Extra sizegroup memberships added after deduplication
 * (surviving_size_id, extra_sizegroup_id, seq_within_that_sizegroup):
 *
 *   S  (1)  + SML sizegroup   (5, seq 50)
 *   M  (2)  + SML sizegroup   (5, seq 51)
 *   L  (3)  + SML sizegroup   (5, seq 52)
 *   2-3 yr (10)  + Children/23 (23, seq 200)
 *   4-5 yr (11)  + Children/23 (23, seq 201)
 *   34 (28)  + Shoe/children  (9, seq 117)  + All shoe (26, seq 261)
 *   35 (29)  + Shoe/children  (9, seq 118)  + All shoe (26, seq 262)
 *   36 (30)  + All shoe       (26, seq 263)
 *   37 (31)  + All shoe       (26, seq 264)
 *   38 (32)  + All shoe       (26, seq 265)
 *   39 (33)  + Shoe Male  (8, seq 81)   + All shoe (26, seq 266)
 *   40 (34)  + Shoe Male  (8, seq 82)   + All shoe (26, seq 267)
 *   41 (35)  + Shoe Male  (8, seq 83)   + All shoe (26, seq 268)
 *   19-24 mo (44)  + Baby/2   (2, seq 43)
 *   7-12 mo  (47)  + Baby/2   (2, seq 41)
 *   13-18 mo (48)  + Baby/2   (2, seq 42)
 *   Mixed (52) + XS-XXL (1, seq 6)  + SML (5, seq 53)
 *              + Baby/2 (2, seq 999)  + Shoe/F (3, seq 999)
 *              + Children/4 (4, seq 999) + One size (7, seq 999)
 *              + Shoe Male (8, seq 999)  + Shoe ch (9, seq 999)
 *              + Diaper (12, seq 999)    + Bra (13, seq 999)
 *              + Pack (16, seq 999)      + Ch/17 (17, seq 999)
 *              + Ch/18 (18, seq 999)     + Pack19 (19, seq 999)
 *              + Ch/20 (20, seq 999)     + Baby/21 (21, seq 999)
 *              + Baby/22 (22, seq 999)   + Ch/23 (23, seq 999)
 *              + Ch/24 (24, seq 999)     + Ch/25 (25, seq 999)
 *              + AllShoe (26, seq 999)   + Socks (27, seq 999)
 *   42 (60)  + All shoe (26, seq 269)
 *   43 (61)  + All shoe (26, seq 270)
 *   44 (62)  + All shoe (26, seq 271)
 *   45 (63)  + All shoe (26, seq 272)
 *   All ages (97) + Baby/2 (2, seq 44)  + Children/4 (4, seq 182)
 *                 + Children/23 (23, seq 204) + Children/24 (24, seq 234)
 *   2-5 yr (116) + Children/20 (20, seq 30)
 *   6-10 yr (117) + Children/18 (18, seq 20)
 *   11-15 yr (118) + Children/18 (18, seq 21)
 *   0-6 mo (119) + Baby/22 (22, seq 180)
 *   24 (18) + All shoe (26, seq 251)
 *   25 (19) + All shoe (26, seq 252)
 *   26 (20) + All shoe (26, seq 253)
 *   27 (21) + All shoe (26, seq 254)
 *   28 (22) + All shoe (26, seq 255)
 *   29 (23) + All shoe (26, seq 256)
 *   30 (24) + All shoe (26, seq 257)
 *   31 (25) + All shoe (26, seq 258)
 *   32 (26) + All shoe (26, seq 259)
 *   33 (27) + All shoe (26, seq 260)
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
        // 2. Populate from existing sizes.sizegroup_id (one row per size)
        // ----------------------------------------------------------------
        $this->execute('INSERT INTO `sizes_sizegroup` (`size_id`, `sizegroup_id`, `seq`)
            SELECT `id`, `sizegroup_id`, `seq`
            FROM   `sizes`
            WHERE  `sizegroup_id` IS NOT NULL');

        // ----------------------------------------------------------------
        // 3. Insert the extra sizegroup memberships inherited from the
        //    duplicate sizes that were removed in the previous migration.
        // ----------------------------------------------------------------
        $this->execute('INSERT INTO `sizes_sizegroup` (`size_id`, `sizegroup_id`, `seq`) VALUES
            -- S (1): was also in SML sizegroup (id 5) via removed id 53
            (1, 5, 50),
            -- M (2): was also in SML sizegroup (id 5) via removed id 54
            (2, 5, 51),
            -- L (3): was also in SML sizegroup (id 5) via removed id 55
            (3, 5, 52),
            -- 2-3 years (10): was also in Children/23 via removed id 141
            (10, 23, 200),
            -- 4-5 years (11): was also in Children/23 via removed id 142
            (11, 23, 201),
            -- shoe sizes 24-33 (ids 18-27): also in All shoe (26) via removed ids 171-180
            (18, 26, 251), (19, 26, 252), (20, 26, 253), (21, 26, 254), (22, 26, 255),
            (23, 26, 256), (24, 26, 257), (25, 26, 258), (26, 26, 259), (27, 26, 260),
            -- 34 (28): also in Shoe/children (9) and All shoe (26) via removed ids 67,181
            (28, 9, 117), (28, 26, 261),
            -- 35 (29): also in Shoe/children (9) and All shoe (26) via removed ids 65,182
            (29, 9, 118), (29, 26, 262),
            -- 36-38 (30-32): also in All shoe (26) via removed ids 183-185
            (30, 26, 263), (31, 26, 264), (32, 26, 265),
            -- 39 (33): also in Shoe Male (8) and All shoe (26) via removed ids 57,186
            (33, 8, 81), (33, 26, 266),
            -- 40 (34): also in Shoe Male (8) and All shoe (26) via removed ids 58,187
            (34, 8, 82), (34, 26, 267),
            -- 41 (35): also in Shoe Male (8) and All shoe (26) via removed ids 59,188
            (35, 8, 83), (35, 26, 268),
            -- 19-24 months (44): also in Baby/2 via removed id 122
            (44, 2, 43),
            -- 7-12 months (47): also in Baby/2 via removed id 120
            (47, 2, 41),
            -- 13-18 months (48): also in Baby/2 via removed id 121
            (48, 2, 42),
            -- Mixed (52): absorbed duplicates from every other sizegroup
            (52,  1,   6), (52,  2, 999), (52,  3, 999), (52,  4, 999),
            (52,  5,  53), (52,  7, 999), (52,  8, 999), (52,  9, 999),
            (52, 12, 999), (52, 13, 999), (52, 16, 999), (52, 17, 999),
            (52, 18, 999), (52, 19, 999), (52, 20, 999), (52, 21, 999),
            (52, 22, 999), (52, 23, 999), (52, 24, 999), (52, 25, 999),
            (52, 26, 999), (52, 27, 999),
            -- 42-45 (60-63): also in All shoe (26) via removed ids 189-192
            (60, 26, 269), (61, 26, 270), (62, 26, 271), (63, 26, 272),
            -- All ages (97): absorbed duplicates from Baby/2, Children/4, Children/23, Children/24
            (97,  2,  44), (97,  4, 182), (97, 23, 204), (97, 24, 234),
            -- 2-5 years (116): also in Children/20 via removed id 130
            (116, 20, 30),
            -- 6-10 years (117): also in Children/18 via removed id 124
            (117, 18, 20),
            -- 11-15 years (118): also in Children/18 via removed id 125
            (118, 18, 21),
            -- 0-6 months (119): also in Baby/22 via removed id 138
            (119, 22, 180)
        ');

        // ----------------------------------------------------------------
        // 4. Drop the now-redundant columns from sizes
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
