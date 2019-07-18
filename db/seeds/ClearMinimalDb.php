<?php

use Phinx\Seed\AbstractSeed;

class ClearMinimalDb extends AbstractSeed
{
    public function run()
    {
        // Generated from a mysql data dump
        // using https://regexr.com and the 'list view'
        // with the regex: 
        // /INSERT INTO `([^`]*)`.*/g
        // and replacement pattern:
        // $this->execute("DELETE FROM `$1`");\n
        $this->execute("DELETE FROM `borrow_categories`");
        $this->execute("DELETE FROM `borrow_items`");
        $this->execute("DELETE FROM `borrow_locations`");
        $this->execute("DELETE FROM `cms_users`");
        $this->execute("DELETE FROM `camps`");
        $this->execute("DELETE FROM `cms_functions`");
        $this->execute("DELETE FROM `cms_functions_camps`");
        $this->execute("DELETE FROM `cms_settings`");
        $this->execute("DELETE FROM `cms_usergroups`");
        $this->execute("DELETE FROM `cms_usergroups_camps`");
        $this->execute("DELETE FROM `cms_usergroups_functions`");
        $this->execute("DELETE FROM `cms_usergroups_levels`");
        $this->execute("DELETE FROM `genders`");
        $this->execute("DELETE FROM `history`");
        $this->execute("DELETE FROM `languages`");
        $this->execute("DELETE FROM `laundry_appointments`");
        $this->execute("DELETE FROM `laundry_machines`");
        $this->execute("DELETE FROM `laundry_slots`");
        $this->execute("DELETE FROM `laundry_stations`");
        $this->execute("DELETE FROM `laundry_times`");
        $this->execute("DELETE FROM `locations`");
        $this->execute("DELETE FROM `log`");
        $this->execute("DELETE FROM `need_periods`");
        $this->execute("DELETE FROM `numbers`");
        $this->execute("DELETE FROM `organisations`");
        $this->execute("DELETE FROM `people`");
        $this->execute("DELETE FROM `product_categories`");
        $this->execute("DELETE FROM `qr`");
        $this->execute("DELETE FROM `sizegroup`");
        $this->execute("DELETE FROM `tipofday`");
        $this->execute("DELETE FROM `transactions`");
        $this->execute("DELETE FROM `translate`");
        $this->execute("DELETE FROM `units`");
    }
}
