<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Removes the audit columns (created, created_by, modified, modified_by) from the
 * sizes table.  These columns are all NULL in every known environment and are not
 * referenced by any application code.
 */
final class DropAuditColumnsFromSizes extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('sizes');
        $table->dropForeignKey('created_by')->save();
        $table->dropForeignKey('modified_by')->save();
        $table->removeColumn('created')
            ->removeColumn('created_by')
            ->removeColumn('modified')
            ->removeColumn('modified_by')
            ->save()
        ;
    }

    public function down(): void
    {
        $this->execute('ALTER TABLE `sizes`
            ADD COLUMN `created`     DATETIME     DEFAULT NULL,
            ADD COLUMN `created_by`  INT UNSIGNED DEFAULT NULL,
            ADD COLUMN `modified`    DATETIME     DEFAULT NULL,
            ADD COLUMN `modified_by` INT UNSIGNED DEFAULT NULL,
            ADD KEY `created_by`  (`created_by`),
            ADD KEY `modified_by` (`modified_by`),
            ADD CONSTRAINT `sizes_ibfk_5`
                FOREIGN KEY (`created_by`)  REFERENCES `cms_users` (`id`)
                ON UPDATE CASCADE,
            ADD CONSTRAINT `sizes_ibfk_6`
                FOREIGN KEY (`modified_by`) REFERENCES `cms_users` (`id`)
                ON UPDATE CASCADE');
    }
}
