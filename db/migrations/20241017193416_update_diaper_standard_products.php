<?php

use Phinx\Migration\AbstractMigration;

class UpdateDiaperStandardProducts extends AbstractMigration
{
    public function up(): void
    {
        $this->execute(
            '
DELETE FROM standard_product
WHERE id >= 80 AND id < 87;'
        );

        $this->execute(
            '
UPDATE standard_product
SET
    name = "Diapers",
    size_range_id = 12
WHERE id = 87;'
        );
    }


    public function down(): void
    {
        $this->execute(
            '
INSERT INTO standard_product
(id,gender_id,name,size_range_id,category_id,version,added_by,added_on)
VALUES
(80,9,"Diapers Size 0",12,10,1,1,UTC_TIMESTAMP()),
(81,9,"Diapers Size 1",12,10,1,1,UTC_TIMESTAMP()),
(82,9,"Diapers Size 2",12,10,1,1,UTC_TIMESTAMP()),
(83,9,"Diapers Size 3",12,10,1,1,UTC_TIMESTAMP()),
(84,9,"Diapers Size 4",12,10,1,1,UTC_TIMESTAMP()),
(85,9,"Diapers Size 5",12,10,1,1,UTC_TIMESTAMP()),
(86,9,"Diapers Size 6",12,10,1,1,UTC_TIMESTAMP());'
        );

        $this->execute(
            '
UPDATE standard_product
SET
    name = "Diapers, Unsized",
    size_range_id = 6
WHERE id = 87;'
        );
    }
}
