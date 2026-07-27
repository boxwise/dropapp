<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UpdateInsightMenu extends AbstractMigration
{
    public function up(): void
    {
        // Rename "Insight" menu and move to top
        $this->execute("
            UPDATE `cms_functions` SET `title_en` = 'Statistics', seq = 0 WHERE `id` = '128';
        ");
        // Put "Sales report" below "Dashboard v2"
        $this->execute("
            UPDATE `cms_functions` SET `seq` = 19 WHERE `id` = '96';
        ");
        // Remove "Fancy graphs"
        $this->execute("
            DELETE FROM `cms_functions` WHERE `id` = '102';
        ");
    }

    public function down(): void
    {
        $this->execute("
            UPDATE `cms_functions` SET `title_en` = 'Insight', seq = 6 WHERE `id` = '128';
        ");
        $this->execute("
            UPDATE `cms_functions` SET `seq` = 16 WHERE `id` = '96';
        ");
        $this->execute("
            INSERT INTO `cms_functions` (`id`, `parent_id`, `title_en`, `include`, `seq`, `alert`, `adminonly`, `visible`, `allusers`, `allcamps`, `action_permissions`) VALUES (102,128,'Fancy Graphs (<span>beta</span>)','fancygraphs',17,0,0,1,0,0,'view_beneficiary_graph');
        ");
    }
}
