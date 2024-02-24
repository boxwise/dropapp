<?php

use Phinx\Migration\AbstractMigration;

class MakeLabelFieldsNotNullable extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('cms_usergroups');
        $table->changeColumn('label', 'string', ['null' => false])
            ->update()
        ;

        $table = $this->table('cms_usergroups_levels');
        $table->changeColumn('label', 'string', ['null' => false])
            ->update()
        ;

        $table = $this->table('organisations');
        $table->changeColumn('label', 'string', ['null' => false])
            ->update()
        ;

        $table = $this->table('product_categories');
        $table->changeColumn('label', 'string', ['null' => false])
            ->update()
        ;

        $table = $this->table('sizegroup');
        $table->changeColumn('label', 'string', ['null' => false])
            ->update()
        ;
    }

    public function down(): void
    {
        $table = $this->table('cms_usergroups');
        $table->changeColumn('label', 'string', ['null' => true])
            ->update()
        ;

        $table = $this->table('cms_usergroups_levels');
        $table->changeColumn('label', 'string', ['null' => true])
            ->update()
        ;

        $table = $this->table('organisations');
        $table->changeColumn('label', 'string', ['null' => true])
            ->update()
        ;

        $table = $this->table('product_categories');
        $table->changeColumn('label', 'string', ['null' => true])
            ->update()
        ;

        $table = $this->table('sizegroup');
        $table->changeColumn('label', 'string', ['null' => true])
            ->update()
        ;
    }
}
