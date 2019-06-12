<?php

use Phinx\Seed\AbstractSeed;

class ClearMinimalDb extends AbstractSeed
{
    public function run()
    {
        $this->execute("DELETE FROM `borrow_categories`");
        $this->execute("DELETE FROM `borrow_locations`");
        $this->execute("DELETE FROM `camps`");
        $this->execute("DELETE FROM `cms_functions`");
        $this->execute("DELETE FROM `cms_functions_camps`");
        $this->execute("DELETE FROM `cms_users`");
        $this->execute("DELETE FROM `genders`");
        $this->execute("DELETE FROM `languages`");
        $this->execute("DELETE FROM `laundry_machines`");
        $this->execute("DELETE FROM `laundry_slots`");
        $this->execute("DELETE FROM `laundry_stations`");
        $this->execute("DELETE FROM `laundry_times`");
        $this->execute("DELETE FROM `locations`");
        $this->execute("DELETE FROM `need_periods`");
        $this->execute("DELETE FROM `numbers`");
        $this->execute("DELETE FROM `product_categories`");
        $this->execute("DELETE FROM `settings`");
        $this->execute("DELETE FROM `settings_categories`");
        $this->execute("DELETE FROM `sizegroup`");
        $this->execute("DELETE FROM `tipofday`");
        $this->execute("DELETE FROM `translate`");
        $this->execute("DELETE FROM `translate`");
        $this->execute("DELETE FROM `translate`");
        $this->execute("DELETE FROM `translate`");
        $this->execute("DELETE FROM `units`");
    }
}
