<?php

use Phinx\Migration\AbstractMigration;

class MakeGendersIdUnsigned extends AbstractMigration
{
    public function up()
    {
        $this->table('products')
            ->dropForeignKey('gender_id')
            ->save()
        ;

        $this->table('genders')
            ->changeColumn('id', 'integer', ['signed' => false])
            ->save()
        ;

        $this->table('products')
            ->changeColumn('gender_id', 'integer', ['signed' => false, 'null' => false])
            ->addForeignKey('gender_id', 'genders', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
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
