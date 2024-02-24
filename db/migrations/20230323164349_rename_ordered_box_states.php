<?php

use Phinx\Migration\AbstractMigration;

class RenameOrderedBoxStates extends AbstractMigration
{
    public function up(): void
    {
        $this->execute('UPDATE box_state SET label="MarkedForShipment" WHERE id=3');
        $this->execute('UPDATE box_state SET label="Received" WHERE id=4');
    }

    public function down(): void
    {
        $this->execute('UPDATE box_state SET label="Ordered" WHERE id=3');
        $this->execute('UPDATE box_state SET label="Picked" WHERE id=4');
    }
}
