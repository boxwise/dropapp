<?php

use Phinx\Migration\AbstractMigration;

class AddInTransitBoxState extends AbstractMigration
{
    public function up(): void
    {
        $this->execute('INSERT INTO box_state(id,label) values (7, "InTransit")');
    }

    public function down(): void
    {
        $this->execute('DELETE FROM box_state WHERE id = 7');
    }
}
