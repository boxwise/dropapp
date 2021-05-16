<?php

use Phinx\Migration\AbstractMigration;

class MakeCmsUsersIdUnsigned extends AbstractMigration
{
    public function up()
    {
        $this->table('borrow_items')
            ->dropForeignKey('modified_by')
            ->dropForeignKey('created_by')
            ->save()
        ;

        $this->table('borrow_transactions')
            ->dropForeignKey('modified_by')
            ->dropForeignKey('created_by')
            ->save()
        ;

        $this->table('camps')
            ->dropForeignKey('modified_by')
            ->dropForeignKey('created_by')
            ->save()
        ;

        $this->table('cms_functions')
            ->dropForeignKey('modified_by')
            ->dropForeignKey('created_by')
            ->save()
        ;

        $this->table('cms_settings')
            ->dropForeignKey('modified_by')
            ->dropForeignKey('created_by')
            ->save()
        ;

        $this->table('cms_usergroups')
            ->dropForeignKey('modified_by')
            ->dropForeignKey('created_by')
            ->save()
        ;

        $this->table('cms_users')
            ->dropForeignKey('modified_by')
            ->dropForeignKey('created_by')
            ->save()
        ;

        $this->table('genders')
            ->dropForeignKey('modified_by')
            ->dropForeignKey('created_by')
            ->save()
        ;

        $this->table('history')
            ->dropForeignKey('user_id')
            ->save()
        ;

        $this->table('laundry_appointments')
            ->dropForeignKey('modified_by')
            ->dropForeignKey('created_by')
            ->save()
        ;

        $this->table('library')
            ->dropForeignKey('modified_by')
            ->dropForeignKey('created_by')
            ->save()
        ;
        $this->table('library_transactions')
            ->dropForeignKey('modified_by')
            ->dropForeignKey('created_by')
            ->save()
        ;

        $this->table('locations')
            ->dropForeignKey('modified_by')
            ->dropForeignKey('created_by')
            ->save()
        ;

        $this->table('organisations')
            ->dropForeignKey('modified_by')
            ->dropForeignKey('created_by')
            ->save()
        ;
        $this->table('people')
            ->dropForeignKey('modified_by')
            ->dropForeignKey('created_by')
            ->save()
        ;

        $this->table('products')
            ->dropForeignKey('modified_by')
            ->dropForeignKey('created_by')
            ->save()
        ;

        $this->table('sizes')
            ->dropForeignKey('modified_by')
            ->dropForeignKey('created_by')
            ->save()
        ;

        $this->table('stock')
            ->dropForeignKey('modified_by')
            ->dropForeignKey('created_by')
            ->dropForeignKey('ordered_by')
            ->dropForeignKey('picked_by')
            ->save()
        ;

        $this->table('tags')
            ->dropForeignKey('modified_by')
            ->dropForeignKey('created_by')
            ->save()
        ;

        $this->table('translate')
            ->dropForeignKey('modified_by')
            ->dropForeignKey('created_by')
            ->save()
        ;

        $this->table('transactions')
            ->dropForeignKey('modified_by')
            ->dropForeignKey('created_by')
            ->dropForeignKey('user_id')
            ->save()
        ;

        $this->table('units')
            ->dropForeignKey('modified_by')
            ->dropForeignKey('created_by')
            ->save()
        ;

        // --------------

        $this->table('cms_users')
            ->changeColumn('id', 'integer', ['signed' => false])
            ->changeColumn('created_by', 'integer', ['signed' => false, 'null' => true])
            ->changeColumn('modified_by', 'integer', ['signed' => false, 'null' => true])
            ->addForeignKey('created_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->addForeignKey('modified_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        // --------------

        $this->table('borrow_items')
            ->changeColumn('created_by', 'integer', ['signed' => false, 'null' => true])
            ->changeColumn('modified_by', 'integer', ['signed' => false, 'null' => true])
            ->addForeignKey('created_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->addForeignKey('modified_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        $this->table('borrow_transactions')
            ->changeColumn('created_by', 'integer', ['signed' => false, 'null' => true])
            ->changeColumn('modified_by', 'integer', ['signed' => false, 'null' => true])
            ->addForeignKey('created_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->addForeignKey('modified_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        $this->table('camps')
            ->changeColumn('created_by', 'integer', ['signed' => false, 'null' => true])
            ->changeColumn('modified_by', 'integer', ['signed' => false, 'null' => true])
            ->addForeignKey('created_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->addForeignKey('modified_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        $this->table('cms_functions')
            ->changeColumn('created_by', 'integer', ['signed' => false, 'null' => true])
            ->changeColumn('modified_by', 'integer', ['signed' => false, 'null' => true])
            ->addForeignKey('created_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->addForeignKey('modified_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        $this->table('cms_settings')
            ->changeColumn('created_by', 'integer', ['signed' => false, 'null' => true])
            ->changeColumn('modified_by', 'integer', ['signed' => false, 'null' => true])
            ->addForeignKey('created_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->addForeignKey('modified_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        $this->table('cms_usergroups')
            ->changeColumn('created_by', 'integer', ['signed' => false, 'null' => true])
            ->changeColumn('modified_by', 'integer', ['signed' => false, 'null' => true])
            ->addForeignKey('created_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->addForeignKey('modified_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        $this->table('genders')
            ->changeColumn('created_by', 'integer', ['signed' => false, 'null' => true])
            ->changeColumn('modified_by', 'integer', ['signed' => false, 'null' => true])
            ->addForeignKey('created_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->addForeignKey('modified_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        $this->table('history')
            ->changeColumn('user_id', 'integer', ['signed' => false, 'null' => true])
            ->addForeignKey('user_id', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        $this->table('laundry_appointments')
            ->changeColumn('created_by', 'integer', ['signed' => false, 'null' => true])
            ->changeColumn('modified_by', 'integer', ['signed' => false, 'null' => true])
            ->addForeignKey('created_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->addForeignKey('modified_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        $this->table('library')
            ->changeColumn('created_by', 'integer', ['signed' => false, 'null' => true])
            ->changeColumn('modified_by', 'integer', ['signed' => false, 'null' => true])
            ->addForeignKey('created_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->addForeignKey('modified_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->save()
        ;
        $this->table('library_transactions')
            ->changeColumn('created_by', 'integer', ['signed' => false, 'null' => true])
            ->changeColumn('modified_by', 'integer', ['signed' => false, 'null' => true])
            ->addForeignKey('created_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->addForeignKey('modified_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        $this->table('locations')
            ->changeColumn('created_by', 'integer', ['signed' => false, 'null' => true])
            ->changeColumn('modified_by', 'integer', ['signed' => false, 'null' => true])
            ->addForeignKey('created_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->addForeignKey('modified_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        $this->table('organisations')
            ->changeColumn('created_by', 'integer', ['signed' => false, 'null' => true])
            ->changeColumn('modified_by', 'integer', ['signed' => false, 'null' => true])
            ->addForeignKey('created_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->addForeignKey('modified_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->save()
        ;
        $this->table('people')
            ->changeColumn('created_by', 'integer', ['signed' => false, 'null' => true])
            ->changeColumn('modified_by', 'integer', ['signed' => false, 'null' => true])
            ->addForeignKey('created_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->addForeignKey('modified_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        $this->table('products')
            ->changeColumn('created_by', 'integer', ['signed' => false, 'null' => true])
            ->changeColumn('modified_by', 'integer', ['signed' => false, 'null' => true])
            ->addForeignKey('created_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->addForeignKey('modified_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        $this->table('sizes')
            ->changeColumn('created_by', 'integer', ['signed' => false, 'null' => true])
            ->changeColumn('modified_by', 'integer', ['signed' => false, 'null' => true])
            ->addForeignKey('created_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->addForeignKey('modified_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        $this->table('stock')
            ->changeColumn('created_by', 'integer', ['signed' => false, 'null' => true])
            ->changeColumn('modified_by', 'integer', ['signed' => false, 'null' => true])
            ->addForeignKey('created_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->addForeignKey('modified_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])

            ->changeColumn('ordered_by', 'integer', ['signed' => false, 'null' => true])
            ->changeColumn('picked_by', 'integer', ['signed' => false, 'null' => true])
            ->addForeignKey('ordered_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->addForeignKey('picked_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        $this->table('tags')
            ->changeColumn('created_by', 'integer', ['signed' => false, 'null' => true])
            ->changeColumn('modified_by', 'integer', ['signed' => false, 'null' => true])
            ->addForeignKey('created_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->addForeignKey('modified_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        $this->table('translate')
            ->changeColumn('created_by', 'integer', ['signed' => false, 'null' => true])
            ->changeColumn('modified_by', 'integer', ['signed' => false, 'null' => true])
            ->addForeignKey('created_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->addForeignKey('modified_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        $this->table('transactions')
            ->changeColumn('created_by', 'integer', ['signed' => false, 'null' => true])
            ->changeColumn('modified_by', 'integer', ['signed' => false, 'null' => true])
            ->addForeignKey('created_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->addForeignKey('modified_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->changeColumn('user_id', 'integer', ['signed' => false, 'null' => true])
            ->addForeignKey('user_id', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        $this->table('units')
            ->changeColumn('created_by', 'integer', ['signed' => false, 'null' => true])
            ->changeColumn('modified_by', 'integer', ['signed' => false, 'null' => true])
            ->addForeignKey('created_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->addForeignKey('modified_by', 'cms_users', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
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
