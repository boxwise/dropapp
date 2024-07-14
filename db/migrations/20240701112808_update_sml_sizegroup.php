<?php

use Phinx\Migration\AbstractMigration;

class UpdateSmlSizegroup extends AbstractMigration
{
    public function up(): void
    {
        $this->execute(
            '
UPDATE sizegroup
SET label = "XS, S, M, L, XL, XXL"
WHERE id = 1;'
        );

        $this->execute(
            '
INSERT INTO sizes
(id,label,sizegroup_id,seq)
VALUES
(203,"XXL",1,7);'
        );
    }

    public function down(): void
    {
        $this->execute(
            '
UPDATE sizegroup
SET label = "XS, S, M, L, XL"
WHERE id = 1;'
        );

        $this->execute('DELETE FROM sizes WHERE id = 203;');
    }
}
