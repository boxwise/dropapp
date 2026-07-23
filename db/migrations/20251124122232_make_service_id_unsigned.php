<?php

use Phinx\Migration\AbstractMigration;

class MakeServiceIdUnsigned extends AbstractMigration
{
    public function up(): void
    {
        $this->table('services_relations')
            ->dropForeignKey('service_id')
            ->save()
        ;

        $this->table('services')
            ->changeColumn('id', 'integer', ['identity' => true, 'signed' => false])
            ->save()
        ;

        $this->table('services_relations')
            ->changeColumn('service_id', 'integer', ['signed' => false, 'null' => false])
            ->addForeignKey('service_id', 'services', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        $this->table('services_relations')
            ->changeColumn('id', 'integer', ['identity' => true, 'signed' => false])
            ->save()
        ;
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->table('services_relations')
            ->dropForeignKey('service_id')
            ->save()
        ;

        $this->table('services')
            ->changeColumn('id', 'integer', ['identity' => true, 'signed' => true])
            ->save()
        ;

        $this->table('services_relations')
            ->changeColumn('service_id', 'integer', ['signed' => true, 'null' => false])
            ->addForeignKey('service_id', 'services', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])
            ->save()
        ;

        $this->table('services_relations')
            ->changeColumn('id', 'integer', ['identity' => true, 'signed' => true])
            ->save()
        ;
    }
}
