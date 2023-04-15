<?php

use Phinx\Migration\AbstractMigration;

class RenameReceivingBoxState extends AbstractMigration
{
    public function up()
    {
        $this->execute('UPDATE box_state SET label="Receiving" WHERE id=4');
    }

    public function down()
    {
        $this->execute('UPDATE box_state SET label="Received" WHERE id=4');
    }
}
