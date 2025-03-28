<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddAdditionalFieldsToPeople extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $this->table('people')
            ->addColumn('customfield1_value', 'string', ['default' => '', 'null' => false])
            ->addColumn('customfield2_value', 'string', ['default' => '', 'null' => false])
            ->addColumn('customfield3_value', 'string', ['default' => '', 'null' => false])
            ->addColumn('customfield4_value', 'datetime', ['default' => null, 'null' => true])
            ->save()
        ;
    }
}
