<?php

use Phinx\Migration\AbstractMigration;

class UpdateBoxStateOfLocations extends AbstractMigration
{
    public function up(): void
    {
        // set box states of locations
        $this->execute('
                UPDATE 
                    locations l 
                SET 
                    l.box_state_id = 2,
                    l.visible = 0,
                    l.is_lost = 1,
                    l.deleted = "2022-12-21 18:00:00"
                WHERE
                    l.is_lost OR l.label LIKE "%LOST%";
            ');

        $this->execute('
                UPDATE 
                    locations l 
                SET 
                    l.box_state_id = 6,
                    l.visible = 0,
                    l.is_scrap = 1,
                    l.deleted = "2022-12-21 18:00:00"
                WHERE
                    l.is_scrap OR l.label LIKE "%SCRAP%";
            ');

        $this->execute('
                UPDATE 
                    locations l 
                SET 
                    l.box_state_id = 5
                WHERE
                    NOT l.visible AND 
                    NOT (l.is_lost OR
                        l.label LIKE "%LOST%" OR
                        l.is_scrap OR 
                        l.label LIKE "%SCRAP%")
            ');

        $this->execute('
            UPDATE 
                locations l 
            SET 
                l.box_state_id = 1
            WHERE
                l.visible AND 
                NOT (l.is_lost OR
                    l.label LIKE "%LOST%" OR
                    l.is_scrap OR 
                    l.label LIKE "%SCRAP%")
        ');
    }

    /**
     * Migrate Down.
     */
    public function down() {}
}
