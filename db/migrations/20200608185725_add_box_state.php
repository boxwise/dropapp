<?php

use Phinx\Migration\AbstractMigration;

class AddBoxState extends AbstractMigration
{
    public function change()
    {
        // create Box State table
        $boxstate = $this->table('box_state');
        $boxstate->addColumn('label', 'string', ['limit' => 20])
            ->addIndex(['label'], ['unique' => true])
            ->create()
        ;

        // Fill with Box States
        $states = [
            [
                'id' => 1,
                'label' => 'Instock',
            ],
            [
                'id' => 2,
                'label' => 'Lost',
            ],
            [
                'id' => 3,
                'label' => 'Ordered',
            ],
            [
                'id' => 4,
                'label' => 'Picked',
            ],
            [
                'id' => 5,
                'label' => 'Donated',
            ],
            [
                'id' => 6,
                'label' => 'Scrap',
            ],
        ];
        $this->table('box_state')->insert($states)->save();

        // add box state to stock table
        $this->table('stock')
            ->addColumn('box_state_id', 'integer', ['default' => 1, 'null' => false])
            ->save();

        // set box states of stock
        $this->execute('
            UPDATE 
                stock s
            LEFT JOIN 
                locations l ON l.id=s.location_id
            SET 
                s.box_state_id = 2
            WHERE
                l.is_lost
        ');

        $this->execute('
            UPDATE 
                stock s
            SET 
                s.box_state_id = 3
            WHERE
                s.ordered
        ');

        $this->execute('
            UPDATE 
                stock s
            SET 
                s.box_state_id = 4
            WHERE
                s.picked
        ');

        $this->execute('
            UPDATE 
                stock s
            LEFT JOIN 
                locations l ON l.id=s.location_id
            SET 
                s.box_state_id = 6
            WHERE
                l.is_scrap
        ');

        $this->execute('
            UPDATE 
                stock s
            LEFT JOIN 
                locations l ON l.id=s.location_id
            SET 
                s.box_state_id = 5
            WHERE
                NOT l.visible AND NOT l.is_lost AND NOT l.is_scrap
        ');

        // add box state FK to stock table
        $this->table('stock')
            ->addForeignKey('box_state_id', 'box_state', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save();

        // add box state to locations table
        $this->table('locations')
            ->addColumn('box_state_id', 'integer', ['default' => 1, 'null' => true])
            ->save();

        // set box states of locations
        $this->execute('
            UPDATE 
                locations l 
            SET 
                l.box_state_id = 2
            WHERE
                l.is_lost
        ');

        $this->execute('
            UPDATE 
                locations l 
            SET 
                l.box_state_id = 6
            WHERE
                l.is_scrap
        ');

        $this->execute('
            UPDATE 
                locations l 
            SET 
                l.box_state_id = 5
            WHERE
                NOT l.visible AND NOT l.is_lost AND NOT l.is_scrap
        ');

        // add box state FK to locations table
        $this->table('locations')
            ->addForeignKey('box_state_id', 'box_state', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save();
    }
}
