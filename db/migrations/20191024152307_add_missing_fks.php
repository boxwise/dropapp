<?php

use Phinx\Migration\AbstractMigration;

class AddMissingFKs extends AbstractMigration
{
    public function up()
    {
        // this was a duplicate FK on people_id column
        $this->table('borrow_transactions')
            ->dropForeignKey('people_id')
            ->addForeignKey('people_id', 'people', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        $this->table('cms_usergroups')
            ->addForeignKey('created_by', 'cms_users', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])

            ->addForeignKey('modified_by', 'cms_users', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        $this->table('organisations')
            ->addForeignKey('created_by', 'cms_users', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])

            ->addForeignKey('modified_by', 'cms_users', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        $this->execute('UPDATE cms_users p1
            left join cms_users p2 on p2.id=p1.created_by
            SET p1.created_by = NULL 
            where p2.id is null');
        $this->execute('UPDATE cms_users p1
            left join cms_users p2 on p2.id=p1.modified_by
            SET p1.modified_by = NULL 
            where p2.id is null');

        $this->table('cms_users')
            ->addForeignKey('created_by', 'cms_users', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])

            ->addForeignKey('modified_by', 'cms_users', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        $this->table('stock')
            ->addForeignKey('picked_by', 'cms_users', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])

            ->addForeignKey('ordered_by', 'cms_users', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        $this->execute('DELETE FROM transactions WHERE people_id NOT IN (select id from people)');

        $this->table('transactions')
            ->addForeignKey('people_id', 'people', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        $this->execute('UPDATE product_categories SET parent_id = null where parent_id = 0');

        $this->table('product_categories')
            ->addForeignKey('parent_id', 'product_categories', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
        ;
    }
}
