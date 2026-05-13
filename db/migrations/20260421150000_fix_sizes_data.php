<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Cleans up data inconsistencies in the sizes_sizegroup table:
 *
 * 1. Sizegroup 1 (XS, S, M, L, XL, XXL):
 *    Corrects seq values so that XS=1, S=2, M=3, L=4, XL=5, XXL=6, Mixed=999.
 *
 * 2. Sizegroup 23 (Children by year 2-3, 4-5, ..., 14-15):
 *    Corrects seq values so that 2-3y=200, 4-5y=201, 6-7y=202, 8-9y=203,
 *    10-11y=204, 12-13y=205, 14-15y=206, All ages=207, Mixed=999.
 *
 * 3. Sizegroups 17, 18, 22, 25:
 *    Adds the "All ages" size (id=97) to each of these sizegroups.
 */
final class FixSizesData extends AbstractMigration
{
    public function up(): void
    {
        // ----------------------------------------------------------------
        // 1. Fix seq for sizegroup 1 (XS, S, M, L, XL, XXL)
        //    Size IDs: XS=5, S=1, M=2, L=3, XL=4, XXL=203, Mixed=52
        // ----------------------------------------------------------------
        $this->execute('UPDATE `sizes_sizegroup`
            SET `seq` = CASE `size_id`
                WHEN 5   THEN 1
                WHEN 1   THEN 2
                WHEN 2   THEN 3
                WHEN 3   THEN 4
                WHEN 4   THEN 5
                WHEN 203 THEN 6
                WHEN 52  THEN 999
            END
            WHERE `sizegroup_id` = 1
              AND `size_id` IN (5, 1, 2, 3, 4, 203, 52)');

        // ----------------------------------------------------------------
        // 2. Fix seq for sizegroup 23 (Children by year 2-3 to 14-15)
        //    Correct order: 2-3y(10)=200, 4-5y(11)=201, 6-7y(143)=202,
        //    8-9y(144)=203, 10-11y(145)=204, 12-13y(146)=205,
        //    14-15y(147)=206, All ages(97)=207, Mixed(52)=999
        // ----------------------------------------------------------------
        $this->execute('UPDATE `sizes_sizegroup`
            SET `seq` = CASE `size_id`
                WHEN 10  THEN 200
                WHEN 11  THEN 201
                WHEN 143 THEN 202
                WHEN 144 THEN 203
                WHEN 145 THEN 204
                WHEN 146 THEN 205
                WHEN 147 THEN 206
                WHEN 97  THEN 207
                WHEN 52  THEN 999
            END
            WHERE `sizegroup_id` = 23
              AND `size_id` IN (10, 11, 143, 144, 145, 146, 147, 97, 52)');

        // ----------------------------------------------------------------
        // 3. Add "All ages" (size_id=97) to sizegroups 17, 18, 22, 25
        // ----------------------------------------------------------------
        $this->execute('INSERT INTO `sizes_sizegroup` (`size_id`, `sizegroup_id`, `seq`) VALUES
            (97, 17, 13),
            (97, 18, 22),
            (97, 22, 182),
            (97, 25, 246)');
    }

    public function down(): void
    {
        // ----------------------------------------------------------------
        // 3. Remove "All ages" from sizegroups 17, 18, 22, 25
        // ----------------------------------------------------------------
        $this->execute('DELETE FROM `sizes_sizegroup`
            WHERE `size_id` = 97
              AND `sizegroup_id` IN (17, 18, 22, 25)');

        // ----------------------------------------------------------------
        // 2. Restore sizegroup 23 seq values
        // ----------------------------------------------------------------
        $this->execute('UPDATE `sizes_sizegroup`
            SET `seq` = CASE `size_id`
                WHEN 10  THEN 200
                WHEN 11  THEN 201
                WHEN 143 THEN 202
                WHEN 144 THEN 203
                WHEN 145 THEN 201
                WHEN 146 THEN 202
                WHEN 147 THEN 203
                WHEN 97  THEN 204
                WHEN 52  THEN 999
            END
            WHERE `sizegroup_id` = 23
              AND `size_id` IN (10, 11, 143, 144, 145, 146, 147, 97, 52)');

        // ----------------------------------------------------------------
        // 1. Restore sizegroup 1 seq values
        // ----------------------------------------------------------------
        $this->execute('UPDATE `sizes_sizegroup`
            SET `seq` = CASE `size_id`
                WHEN 5   THEN 5
                WHEN 1   THEN 1
                WHEN 2   THEN 2
                WHEN 3   THEN 3
                WHEN 4   THEN 4
                WHEN 203 THEN 7
                WHEN 52  THEN 6
            END
            WHERE `sizegroup_id` = 1
              AND `size_id` IN (5, 1, 2, 3, 4, 203, 52)');
    }
}
