<?php

use Phinx\Migration\AbstractMigration;

class AddInTransitBoxState extends AbstractMigration
{
    public function up()
    {
        $this->execute('INSERT INTO box_state(id,label) values (7, "InTransit")');
    }

    public function down()
    {
        $this->execute('DELETE FROM box_state WHERE id = 7');
    }
}
