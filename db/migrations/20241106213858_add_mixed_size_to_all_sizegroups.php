<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddMixedSizeToAllSizegroups extends AbstractMigration
{
    public function up(): void
    {
        $this->execute('INSERT INTO sizes (sizegroup_id, label, seq) SELECT id, "Mixed", 999 FROM sizegroup WHERE id BETWEEN 2 AND 27 AND id NOT IN (5, 6)');
    }

    public function down(): void
    {
        $this->execute('UPDATE sizes SET sizegroup_id = NULL WHERE label = "Mixed" AND id NOT IN (52, 70,71)');
    }
}
