<?php

use Phinx\Migration\AbstractMigration;

class RenameReceivingBoxState extends AbstractMigration
{
    public function up(): void
    {
        $this->execute('UPDATE box_state SET label="Receiving" WHERE id=4');
    }

    public function down(): void
    {
        $this->execute('UPDATE box_state SET label="Received" WHERE id=4');
    }
}
