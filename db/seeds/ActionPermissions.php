<?php

use Phinx\Seed\AbstractSeed;

class ActionPermissions extends AbstractSeed
{
    public function getDependencies()
    {
        return [
            'Minimal',
        ];
    }

    public function run()
    {
        $this->execute('UPDATE cms_functions SET action_permissions = "checkout_beneficiaries" WHERE id=87');
        $this->execute('UPDATE cms_functions SET action_permissions = "view_stockroom" WHERE id=110');
        $this->execute('UPDATE cms_functions SET action_permissions = "generate_market_schedule" WHERE id=11');
        $this->execute('UPDATE cms_functions SET action_permissions = "manage_tokens" WHERE id=92');
        $this->execute('UPDATE cms_functions SET action_permissions = "manage_products" WHERE id=67');
        $this->execute('UPDATE cms_functions SET action_permissions = "manage_warehouses" WHERE id=115');
        $this->execute('UPDATE cms_functions SET action_permissions = "manage_volunteers,manage_coordinators,manage_admins" WHERE id=43');
        $this->execute('UPDATE cms_functions SET action_permissions = "create_label" WHERE id=112');
        $this->execute('UPDATE cms_functions SET action_permissions = "manage_inventory" WHERE id=90');
        $this->execute('UPDATE cms_functions SET action_permissions = "view_start_page" WHERE id=123');
        $this->execute('UPDATE cms_functions SET action_permissions = "be_user" WHERE id=125');
        $this->execute('UPDATE cms_functions SET action_permissions = "list_sales" WHERE id=96');
        $this->execute('UPDATE cms_functions SET action_permissions = "view_beneficiary_graph" WHERE id=102');
        $this->execute('UPDATE cms_functions SET action_permissions = "manage_books" WHERE id=145');
        $this->execute('UPDATE cms_functions SET action_permissions = "lend_books" WHERE id=146');
        $this->execute('UPDATE cms_functions SET action_permissions = "manage_base,be_god" WHERE id=157');
        $this->execute('UPDATE cms_functions SET action_permissions = "manage_organizations,be_god" WHERE id=154');
        $this->execute('UPDATE cms_functions SET action_permissions = "be_god" WHERE id=150');
        $this->execute('UPDATE cms_functions SET action_permissions = "be_god" WHERE id=44');
        $this->execute('UPDATE cms_functions SET action_permissions = "be_god" WHERE id=45');
        $this->execute('UPDATE cms_functions SET action_permissions = "create_beneficiaries" WHERE id=158');
        $this->execute('UPDATE cms_functions SET action_permissions = "manage_beneficiaries" WHERE id=118');
        $this->execute('UPDATE cms_functions SET action_permissions = "manage_tags" WHERE id=162');
    }
}
