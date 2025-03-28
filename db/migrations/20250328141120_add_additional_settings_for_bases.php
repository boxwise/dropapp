<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddAdditionalSettingsForBases extends AbstractMigration
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
        $this->table('camps')
            ->addColumn('email_enabled', 'boolean', ['default' => 0, 'null' => false])
            ->addColumn('phone_enabled', 'boolean', ['default' => 0, 'null' => false])
            ->addColumn('additional_field1_enabled', 'boolean', ['default' => 0, 'null' => false])
            ->addColumn('additional_field2_enabled', 'boolean', ['default' => 0, 'null' => false])
            ->addColumn('additional_field3_enabled', 'boolean', ['default' => 0, 'null' => false])
            ->addColumn('additional_field4_enabled', 'boolean', ['default' => 0, 'null' => false])
            ->addColumn('additional_field1_label', 'string', ['default' => '', 'null' => false])
            ->addColumn('additional_field2_label', 'string', ['default' => '', 'null' => false])
            ->addColumn('additional_field3_label', 'string', ['default' => '', 'null' => false])
            ->addColumn('additional_field4_label', 'string', ['default' => '', 'null' => false])
            ->save()
        ;
    }
}
