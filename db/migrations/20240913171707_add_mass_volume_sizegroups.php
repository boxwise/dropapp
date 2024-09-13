<?php

use Phinx\Migration\AbstractMigration;

class AddMassVolumeSizegroups extends AbstractMigration
{
    public function up(): void
    {
        $this->execute(
            '
INSERT INTO sizegroup
(id,label,seq)
VALUES
(28,"Mass",120),
(29,"Volume",121);'
        );
    }

    public function down(): void
    {
        $this->execute(
            '
DELETE FROM sizegroup
WHERE id = 28 OR id = 29;'
        );
    }
}
