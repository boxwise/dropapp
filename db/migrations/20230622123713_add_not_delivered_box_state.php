<?php

use Phinx\Migration\AbstractMigration;

class AddNotDeliveredBoxState extends AbstractMigration
{
    public function up(): void
    {
        $this->execute('INSERT INTO box_state(id,label) values (8, "NotDelivered")');
    }

    public function down(): void
    {
        $this->execute('DELETE FROM box_state WHERE id = 8');
    }
}
