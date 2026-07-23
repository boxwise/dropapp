<?php

use Phinx\Migration\AbstractMigration;

class RemoveQrFromHistory extends AbstractMigration
{
    public function up(): void
    {
        // Remove all 140k history entries about QR code creation before 2025 (due
        // to a bug, they were not logged from June 2024 onwards anyways).
        // This keeps entries that stem from the v2 createQrCode mutation from
        // Aug 2025 onwards (these should actually be logged with tablename =
        // "stock" and changes "New Qr-code assigned by pdf generation.")
        $deleted_rows = $this->execute('
DELETE FROM history
WHERE tablename = "qr"
        ');
        $this->output->writeln('Deleted rows: '.$deleted_rows);
    }

    public function down() {}
}
