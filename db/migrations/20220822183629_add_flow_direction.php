<?php

use Phinx\Migration\AbstractMigration;

class AddFlowDirection extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('distro_events_outflow_logs');
        if ($table) {
            $table->addColumn('flow_direction', 'string', [
                'null' => true,
                'limit' => '255',
                'after' => 'location_id',
            ])->addIndex(['flow_direction'], [
                'name' => 'distro_events_outflow_logs_flow_direction',
                'unique' => false,
            ])->save();

            $table->dropForeignKey('location_id')->save();
            $table->changeColumn('location_id', 'integer', [
                'null' => true,
                'signed' => false,
            ])->save();
            $table->addForeignKey('location_id', 'locations', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])->save();
        }
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $table = $this->table('distro_events_outflow_logs');
        if ($table) {
            $table->removeIndex(['flow_direction'])
                ->removeColumn('flow_direction')->save();
            $table->dropForeignKey('location_id')->save();
            $table->changeColumn('location_id', 'integer', [
                'null' => false,
                'signed' => false,
            ])->save();
            $table->addForeignKey('location_id', 'locations', 'id', [
                'delete' => 'RESTRICT', 'update' => 'CASCADE',
            ])->save();
        }
    }
}
