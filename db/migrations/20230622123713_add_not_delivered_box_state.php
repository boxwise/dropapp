<?php

use Phinx\Migration\AbstractMigration;

class AddNotDeliveredBoxState extends AbstractMigration
{
    public function up()
    {
        $this->execute('INSERT INTO box_state(id,label) values (8, "NotDelivered")');
    }

    public function down()
    {
        $this->execute('DELETE FROM box_state WHERE id = 8');
    }
}
