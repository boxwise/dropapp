<?php

use Phinx\Migration\AbstractMigration;

class UpdateUnitsTable extends AbstractMigration
{
    public function up(): void
    {
        $this->table('units')->drop()->save();
        $table = $this->table('units', [
            'id' => true,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'signed' => false,
            'encoding' => 'utf8mb4',
            'collation' => 'utf8_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ]);
        $table->addColumn('name', 'string', [
            'null' => false,
            'limit' => 255,
            'after' => 'id',
        ])
            ->addColumn('symbol', 'string', [
                'null' => false,
                'limit' => 255,
                'after' => 'name',
            ])
            ->addColumn('conversion_factor', 'float', [
                'null' => false,
                'after' => 'symbol',
            ])
            ->addColumn('dimension_id', 'integer', [
                'null' => false,
                'signed' => false,
                'after' => 'conversion_factor',
            ])
            ->addForeignKey('dimension_id', 'sizegroup', 'id', [
                'update' => 'CASCADE',
            ])
            ->create()
        ;

        $this->execute(
            '
INSERT INTO units
(id,name,symbol,conversion_factor,dimension_id)
VALUES
(1,"kilogram","kg",1.0,28),
(2,"liter","l",1.0,29),
(3,"gram","g",1000.0,28),
(4,"milligram","mg",1000000.0,28),
(5,"metric ton","t",0.001,28),
(6,"pound","lb",2.2046,28),
(7,"ounce","oz",35.274,28),
(8,"gallon (US)","gal (US)",0.2642,29),
(9,"pint (US)","pt (US)",2.1134,29),
(10,"fluid ounce (US)","fl oz (US)",33.814,29)
;'
        );
    }

    public function down(): void
    {
        // Just drop the table instead of recreating because it wasn't used
        // anyways
        $this->table('units')->drop()->save();
    }
}
