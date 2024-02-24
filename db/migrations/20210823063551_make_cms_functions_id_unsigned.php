<?php

use Phinx\Migration\AbstractMigration;

class MakeCmsFunctionsIdUnsigned extends AbstractMigration
{
    public function up(): void
    {
        $this->table('cms_functions')
            ->dropForeignKey('parent_id')
            ->save()
        ;

        $this->table('cms_functions_camps')
            ->dropForeignKey('cms_functions_id')
            ->save()
        ;

        $this->table('cms_usergroups_functions')
            ->dropForeignKey('cms_functions_id')
            ->save()
        ;

        $this->table('cms_functions')->changeColumn('id', 'integer', ['identity' => true, 'signed' => false])
            ->save()
        ;

        $this->table('cms_functions')
            ->changeColumn('parent_id', 'integer', ['signed' => false, 'null' => true])
            ->addForeignKey('parent_id', 'cms_functions', 'id', [
                'delete' => 'SET_NULL', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        $this->table('cms_functions_camps')
            ->changeColumn('cms_functions_id', 'integer', ['signed' => false, 'null' => false])
            ->addForeignKey('cms_functions_id', 'cms_functions', 'id', [
                'delete' => 'CASCADE', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        $this->table('cms_usergroups_functions')
            ->changeColumn('cms_functions_id', 'integer', ['signed' => false, 'null' => false])
            ->addForeignKey('cms_functions_id', 'cms_functions', 'id', [
                'delete' => 'CASCADE', 'update' => 'CASCADE',
            ])
            ->save()
        ;
    }

    /**
     * Migrate Down.
     */
    public function down() {}
}
