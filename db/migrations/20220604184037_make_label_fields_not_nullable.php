<?php

use Phinx\Migration\AbstractMigration;

class MakeLabelFieldsNotNullable extends AbstractMigration
{
    public function up()
    {
        $stock = $this->table('cms_usergroups');
        $stock->changeColumn('label', 'string', ['null' => false])
              ->update();

        $stock = $this->table('cms_usergroups_levels');
        $stock->changeColumn('label', 'string', ['null' => false])
                    ->update();

        $stock = $this->table('organisations');
        $stock->changeColumn('label', 'string', ['null' => false])
                          ->update();

        $stock = $this->table('product_categories');
        $stock->changeColumn('label', 'string', ['null' => false])
                                ->update();

        $stock = $this->table('sizegroup');
        $stock->changeColumn('label', 'string', ['null' => false])
                                      ->update();
    }

    public function down()
    {
        $stock = $this->table('cms_usergroups');
        $stock->changeColumn('label', 'string', ['null' => true])
              ->update();

        $stock = $this->table('cms_usergroups_levels');
        $stock->changeColumn('label', 'string', ['null' => true])
                    ->update();

        $stock = $this->table('organisations');
        $stock->changeColumn('label', 'string', ['null' => true])
                          ->update();

        $stock = $this->table('product_categories');
        $stock->changeColumn('label', 'string', ['null' => true])
                                ->update();

        $stock = $this->table('sizegroup');
        $stock->changeColumn('label', 'string', ['null' => true])
                                      ->update();
    }
}
