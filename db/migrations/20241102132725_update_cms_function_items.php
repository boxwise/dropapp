<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UpdateCmsFunctionItems extends AbstractMigration
{
    public function up(): void
    {
        $this->execute('UPDATE cms_functions SET title_en = "Stock Planning" WHERE id = 160');
        $dashboardExists = $this->fetchRow('SELECT 1 FROM cms_functions WHERE id = 167');
        if (!$dashboardExists) {
            $this->execute('INSERT INTO `cms_functions` (`id`, `parent_id`, `title_en`, `include`, `seq`, `created`, `created_by`, `modified`, `modified_by`, `alert`, `adminonly`, `visible`, `allusers`, `allcamps`, `action_permissions`) VALUES (167,128,"Dashboard v2 (<span>beta</span>)","statviz_dashboard",18,NOW(),NULL,NULL,NULL,0,0,1,0,0,"view_beneficiary_graph")');

            // enable Dashboard V2 for all bases that also have Fancy Graphs
            $camps = $this->fetchAll('SELECT camps_id FROM cms_functions_camps WHERE cms_functions_id = 102');
            foreach ($camps as $camp) {
                $this->execute('INSERT INTO cms_functions_camps (cms_functions_id, camps_id) VALUES (167, '.$camp['camps_id'].')');
            }

            // enable Dashboard V2 for all usergroups that also have access to Fancy Graphs and all warehouse volunteers
            $usergroupsFancyGraphs = $this->fetchAll('SELECT cms_usergroups_id FROM cms_usergroups_functions WHERE cms_functions_id = 102');
            $usergroupsVolunteerWarehouse = $this->fetchAll('SELECT id as cms_usergroups_id FROM cms_usergroups WHERE label LIKE "%Volunteer (Warehouse)"');
            $usergroups = array_merge($usergroupsFancyGraphs, $usergroupsVolunteerWarehouse);

            foreach ($usergroups as $usergroup) {
                $this->execute('INSERT INTO cms_usergroups_functions (cms_functions_id, cms_usergroups_id) VALUES (167, '.$usergroup['cms_usergroups_id'].')');
            }
        }
    }

    public function down(): void
    {
        $this->execute('UPDATE cms_functions SET title_en = "Stock Overview" WHERE id = 160');
        $this->execute('DELETE FROM cms_functions WHERE id = 167');
    }
}
