<?php

use Phinx\Migration\AbstractMigration;

class MakePeopleIdUnsigned extends AbstractMigration
{
    public function up()
    {
        $this->table('borrow_transactions')
            ->dropForeignKey('people_id')
            ->save()
        ;

        $this->table('laundry_appointments')
            ->dropForeignKey('people_id')
            ->save()
        ;

        $this->table('library_transactions')
            ->dropForeignKey('people_id')
            ->save()
        ;
        $this->table('people_tags')
            ->dropForeignKey('people_id')
            ->save()
        ;
        $this->table('transactions')
            ->dropForeignKey('people_id')
            ->save()
        ;
        $this->table('x_people_languages')
            ->dropForeignKey('people_id')
            ->save()
        ;
        $this->table('people')
            ->dropForeignKey('parent_id')
            ->save()
        ;

        $this->table('people')
            ->changeColumn('id', 'integer', ['identity' => true, 'signed' => false])
            ->save()
        ;

        $this->table('people')
            ->changeColumn('parent_id', 'integer', ['signed' => false, 'null' => true])
            ->addForeignKey('parent_id', 'people', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        $this->table('borrow_transactions')
            ->changeColumn('people_id', 'integer', ['signed' => false, 'null' => true])
            ->addForeignKey('people_id', 'people', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        $this->table('laundry_appointments')
            ->changeColumn('people_id', 'integer', ['signed' => false, 'null' => true])
            ->addForeignKey('people_id', 'people', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        $this->table('library_transactions')
            ->changeColumn('people_id', 'integer', ['signed' => false, 'null' => true])
            ->addForeignKey('people_id', 'people', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        $this->table('people_tags')
            ->changeColumn('people_id', 'integer', ['signed' => false, 'null' => false])
            ->addForeignKey('people_id', 'people', 'id', [
                'delete' => 'CASCADE', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        $this->table('transactions')
            ->changeColumn('people_id', 'integer', ['signed' => false, 'null' => true])
            ->addForeignKey('people_id', 'people', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        $this->table('x_people_languages')
            ->changeColumn('people_id', 'integer', ['signed' => false, 'null' => false])
            ->addForeignKey('people_id', 'people', 'id', [
                'delete' => 'CASCADE', 'update' => 'CASCADE',
            ])
            ->save()
        ;
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
    }
}
