<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class WidenLocationsLabelColumn extends AbstractMigration
{
    /**
     * Widens locations.label from varchar(20) to varchar(50) so that
     * location names are no longer silently truncated by MySQL on save.
     */
    public function change(): void
    {
        $this->table('locations')
            ->changeColumn('label', 'string', [
                'null' => false,
                'limit' => 50,
            ])
            ->update()
        ;
    }
}
