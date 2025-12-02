<?php

use Phinx\Migration\AbstractMigration;

class ReorderServicesMenu extends AbstractMigration
{
    public function up(): void
    {
        $this->execute('UPDATE cms_functions SET parent_id = 161 WHERE id IN (168, 169);');
        // Add beneficiary
        $this->execute('UPDATE cms_functions SET seq = 1 WHERE id = 158;');
        // Use service
        $this->execute('UPDATE cms_functions SET seq = 2 WHERE id = 168;');
        // Manage beneficiaries
        $this->execute('UPDATE cms_functions SET seq = 3 WHERE id = 118;');
        // Manage services
        $this->execute('UPDATE cms_functions SET seq = 4 WHERE id = 169;');
    }

    public function down(): void
    {
        $this->execute('UPDATE cms_functions SET parent_id = 131 WHERE id IN (168, 169);');
        // Add beneficiary
        $this->execute('UPDATE cms_functions SET seq =  4 WHERE id = 158;');
        // Use service
        $this->execute('UPDATE cms_functions SET seq = 10 WHERE id = 168;');
        // Manage beneficiaries
        $this->execute('UPDATE cms_functions SET seq =  5 WHERE id = 118;');
        // Manage services
        $this->execute('UPDATE cms_functions SET seq = 11 WHERE id = 169;');
    }
}
