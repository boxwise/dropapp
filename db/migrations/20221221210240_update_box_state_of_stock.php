<?php

use Phinx\Migration\AbstractMigration;

class UpdateBoxStateOfStock extends AbstractMigration
{
    public function up()
    {
        // set box states of stock
        $this->execute('
                UPDATE 
                    stock s
                LEFT JOIN 
                    locations l ON l.id = s.location_id 
                SET
                    s.box_state_id = l.box_state_id
                WHERE
                    NOT s.box_state_id = l.box_state_id;
            ');
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
    }
}
