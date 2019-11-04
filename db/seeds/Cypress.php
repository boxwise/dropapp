<?php

use Phinx\Seed\AbstractSeed;

class Cypress extends AbstractSeed
{
    public function getDependencies()
    {
        return [
            'MinimalDb',
        ];
    }

    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        // TestOrganisation
        $this->execute("INSERT INTO `organisations` (`id`, `label`, `created`, `created_by`, `deleted`, `modified`, `modified_by`) VALUES (100000000,'TestOrganisation','2019-07-10 08:05:56',1,NULL,NULL,NULL);");
        $this->execute("INSERT INTO `camps` (`id`, `idcard`, `laundry`, `laundry_cyclestart`, `market`, `maxfooddrops_adult`, `maxfooddrops_child`, `modified`, `modified_by`, `name`, `organisation_id`, `schedulebreak`, `schedulebreakduration`, `schedulebreakstart`, `schedulestart`, `schedulestop`, `scheduletimeslot`, `seq`, `workshop`, `adult_age`, `bicycle`, `bicycle_closingtime`, `bicycle_closingtime_saturday`, `bicyclerenttime`, `created`, `created_by`, `currencyname`, `cyclestart`, `daystokeepdeletedpersons`, `delete_inactive_users`, `deleted`, `dropcapadult`, `dropcapchild`, `dropsperadult`, `dropsperchild`, `extraportion`, `familyidentifier`, `food`) VALUES (100000000,1,0,'2019-01-01',1,25,25,'2019-09-05 13:48:34',1,'TestBase',100000000,'1','1','2019-09-05 13:00:00','2019-09-05 11:00:00','2019-09-05 17:00:00','0.5',3,0,15,0,'2019-09-05 17:30:00','2019-09-05 16:30:00',120,'2019-07-10 08:06:22',1,'Tokens','2019-01-01 00:00:00',9999,99999,NULL,99999,99999,'100','100',0,'Refugee ID',0);");
        $this->execute('INSERT INTO `cms_functions_camps` (`cms_functions_id`, `camps_id`) VALUES (91,100000000),(158,100000000),(87,100000000),(118,100000000),(110,100000000),(111,100000000),(92,100000000),(90,100000000),(112,100000000),(96,100000000),(117,100000000),(102,100000000),(67,100000000),(115,100000000);');
        $this->execute("INSERT INTO `cms_usergroups` (`id`, `label`, `created`, `created_by`, `modified`, `modified_by`, `organisation_id`, `userlevel`, `allow_laundry_startcycle`, `allow_laundry_block`, `allow_borrow_adddelete`, `deleted`) VALUES (100000000,'TestUserGroup_User','2019-07-10 08:06:53',1,NULL,NULL,100000000,3,0,0,0,NULL),(100000001,'TestUserGroup_Coordinator','2019-07-10 08:07:15',1,NULL,NULL,100000000,2,0,0,0,NULL),(100000002,'TestUserGroup_Admin','2019-07-10 08:07:44',1,NULL,NULL,100000000,1,0,0,0,NULL), (100000003,'TestUserGroup_NoPermissions','2019-07-10 08:06:53',1,NULL,NULL,100000000,3,0,0,0,NULL);");
        $this->execute('INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES (158,100000002),(90,100000002),(87,100000002),(102,100000002),(91,100000002),(111,100000002),(112,100000002),(92,100000002),(118,100000002),(67,100000002),(117,100000002),(96,100000002),(110,100000002),(43,100000002),(90,100000000),(87,100000000),(102,100000000),(91,100000000),(112,100000000),(118,100000000),(117,100000000),(96,100000000),(110,100000000),(158,100000001),(90,100000001),(87,100000001),(102,100000001),(91,100000001),(111,100000001),(112,100000001),(92,100000001),(118,100000001),(67,100000001),(117,100000001),(96,100000001),(110,100000001),(43,100000001);');
        $this->execute('INSERT INTO `cms_usergroups_camps` (`camp_id`, `cms_usergroups_id`) VALUES (100000000,100000000),(100000000,100000001),(100000000,100000002), (100000000,100000003);');
        $this->execute("INSERT INTO `cms_users` (`id`, `pass`, `naam`, `organisation_id`, `email`, `is_admin`, `lastlogin`, `lastaction`, `created`, `created_by`, `modified`, `modified_by`, `resetpassword`, `language`, `deleted`, `cms_usergroups_id`, `valid_firstday`, `valid_lastday`) VALUES (100000000,'bb1f40afbf093afa5b9b343625ed08bd','BrowserTestUser_Admin',NULL,'admin@admin.co',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','2019-07-10 08:10:40',1,NULL,NULL,NULL,2,'0000-00-00 00:00:00',100000002,'0000-00-00','0000-00-00'),(100000001,'bb1f40afbf093afa5b9b343625ed08bd','BrowserTestUser_Coordinator',NULL,'coordinator@coordinator.co',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','2019-07-10 08:11:08',1,NULL,NULL,NULL,2,'0000-00-00 00:00:00',100000001,'0000-00-00','0000-00-00'),(100000002,'bb1f40afbf093afa5b9b343625ed08bd','BrowserTestUser_User',NULL,'user@user.co',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','2019-07-10 08:11:31',1,NULL,NULL,NULL,2,'0000-00-00 00:00:00',100000000,'0000-00-00','0000-00-00'),(100000003,'bb1f40afbf093afa5b9b343625ed08bd','NotActived',NULL,'notactivated@notactivated.com',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','2019-07-10 08:14:35',1,NULL,NULL,NULL,2,'0000-00-00 00:00:00',100000000,'2019-07-20','2019-07-31'),(100000004,'bb1f40afbf093afa5b9b343625ed08bd','Expired',NULL,'expired@expired.co',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','2019-07-10 08:14:58',1,NULL,NULL,NULL,2,'0000-00-00 00:00:00',100000000,'2019-07-01','2019-07-09'),(100000005,'bb1f40afbf093afa5b9b343625ed08bd','Deleted',NULL,'deleted@deleted.co.deleted.100000005',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','2019-07-10 08:15:42',1,'2019-07-10 08:15:50',1,NULL,2,'2019-07-10 08:15:50',100000000,'0000-00-00','0000-00-00'), (100000006,'bb1f40afbf093afa5b9b343625ed08bd','BrowserTestUser_UserWithNoPermissions',NULL,'noPermissions@noPermissions.co',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','2019-07-10 08:11:31',1,NULL,NULL,NULL,2,'0000-00-00 00:00:00',100000003,'0000-00-00','0000-00-00');");
        $this->execute("INSERT INTO `locations` (`id`, `label`, `camp_id`, `visible`, `container_stock`, `is_market`, `is_donated`, `is_lost`) VALUES (100000000,'TestShop',100000000,0,0,1,0,0),(100000001,'TestLOST',100000000,0,0,0,0,1),(100000002,'TestDonated',100000000,0,0,0,1,0),(100000003,'TestWarehouse',100000000,1,0,0,0,0),(100000004,'TestStockroom',100000000,1,1,0,0,0);");
        $this->execute("INSERT INTO `products` (`id`, `name`, `category_id`, `gender_id`, `sizegroup_id`, `camp_id`, `value`, `amountneeded`, `created`, `created_by`, `modified`, `modified_by`, `maxperadult`, `maxperchild`, `stockincontainer`, `comments`, `deleted`) VALUES (1158,'Jeans',2,1,5,100000000,50,3,'2019-09-05 13:54:40',1,NULL,NULL,0,0,1,'',NULL),(1159,'T-Shirts',3,2,10,100000000,30,3,'2019-09-05 13:55:10',1,NULL,NULL,0,0,1,'',NULL),(1160,'Trainers',5,6,9,100000000,100,3,'2019-09-05 13:55:49',1,NULL,NULL,0,0,1,'',NULL),(1161,'Sleeping Bag',9,3,7,100000000,100,3,'2019-09-05 13:56:23',1,NULL,NULL,0,0,0,'',NULL),(1162,'Diapers',8,9,2,100000000,0,3,'2019-09-05 13:56:46',1,NULL,NULL,0,0,0,'',NULL),(1163,'Shampoo (100ml)',10,10,7,100000000,20,6,'2019-09-05 13:57:31',1,NULL,NULL,0,0,1,'',NULL),(1164,'Rice (1kg)',11,10,7,100000000,25,3,'2019-09-05 13:57:59',1,NULL,NULL,0,0,1,'',NULL);");
        $this->execute("INSERT INTO `people` (`id`,`firstname`,`lastname`,`camp_id`,`container`,`date_of_birth`,`created`) VALUES (100000001,'User', 'WithoutTokens',100000000,'001','1980-07-10','2019-09-02'), (100000002, 'Conor', 'McGregor',100000000,'002','1980-07-10','2019-09-02'), (100000003, 'Garry', 'Tonon',100000000,'003','1985-07-10','2019-09-01'), (100000004, 'Kron', 'Gracie',100000000,'004','1978-07-10','2019-09-02')");
        $this->execute("INSERT INTO `transactions` (`id`,`people_id`,`product_id`,`size_id`,`count`,`drops`,`transaction_date`, `user_id`) VALUES (1, 100000002, 1158, 1, 0 , 2147483647 ,'2019-09-02', 1),(2, 100000003, 1161, 1, 0 , 2147483647 ,'2019-09-02', 1), (3, 100000004, 1162, 1, 0 , 2147483647 ,'2019-09-02', 1)");
        $this->execute("INSERT INTO `qr` (`id`, `code`, `created`) VALUES (100000000,'093f65e080a295f8076b1c5722a46aa2','2019-09-29 11:12:57'),(100000001,'44f683a84163b3523afe57c2e008bc8c','2019-09-29 15:12:57');");
        $this->execute("INSERT INTO `stock` (`id`, `box_id`, `product_id`, `size_id`,`items`,`location_id`,`qr_id`,`comments`,`created`,`created_by`) VALUES (100000000, 328765, 1163, 68, 50, 100000002, 100000000, '50 shampoo bottles', '2019-09-29 11:15:32', 1);");

        // DummyTestOrgWithBoxes
        $this->execute("INSERT INTO `organisations` (`id`, `label`, `created`, `created_by`, `deleted`, `modified`, `modified_by`) VALUES (100000001,'DummyTestOrgWithBoxes','2019-09-29 08:05:56',1,NULL,NULL,NULL);");
        $this->execute("INSERT INTO `camps` (`id`, `idcard`, `laundry`, `laundry_cyclestart`, `market`, `maxfooddrops_adult`, `maxfooddrops_child`, `modified`, `modified_by`, `name`, `organisation_id`, `schedulebreak`, `schedulebreakduration`, `schedulebreakstart`, `schedulestart`, `schedulestop`, `scheduletimeslot`, `seq`, `workshop`, `adult_age`, `bicycle`, `bicycle_closingtime`, `bicycle_closingtime_saturday`, `bicyclerenttime`, `created`, `created_by`, `currencyname`, `cyclestart`, `daystokeepdeletedpersons`, `delete_inactive_users`, `deleted`, `dropcapadult`, `dropcapchild`, `dropsperadult`, `dropsperchild`, `extraportion`, `familyidentifier`, `food`) VALUES (100000001,1,0,'2019-01-01',1,25,25,'2019-09-05 13:48:34',1,'DummyTestBaseWithBoxes',100000001,'1','1','2019-09-05 13:00:00','2019-09-05 11:00:00','2019-09-05 17:00:00','0.5',3,0,15,0,'2019-09-05 17:30:00','2019-09-05 16:30:00',120,'2019-07-10 08:06:22',1,'Tokens','2019-01-01 00:00:00',9999,30,NULL,99999,99999,'100','100',0,'Refugee ID',0);");
        $this->execute("INSERT INTO `products` (`id`, `name`, `category_id`, `gender_id`, `sizegroup_id`, `camp_id`, `value`, `amountneeded`, `created`, `created_by`, `modified`, `modified_by`, `maxperadult`, `maxperchild`, `stockincontainer`, `comments`, `deleted`) VALUES (1165,'DummyProduct',2,1,5,100000001,50,3,'2019-09-05 13:54:40',1,NULL,NULL,0,0,1,'',NULL);");
        $this->execute("INSERT INTO `locations` (`id`, `label`, `camp_id`, `visible`, `container_stock`, `is_market`, `is_donated`, `is_lost`) VALUES (100000005,'TestDummyLocation',100000001,0,0,1,0,0);");
        $this->execute("INSERT INTO `qr` (`id`, `code`, `created`) VALUES (100000002,'4b382363fa161c111fa9ad2b335ceacd','2019-09-29 17:12:57'), (100000003,'b1cf83ae73adfce0d14dbe81b53cb96b','2019-09-29 18:12:57');");
        $this->execute("INSERT INTO `stock` (`id`, `box_id`, `product_id`, `size_id`,`items`,`location_id`,`qr_id`,`comments`,`created`,`created_by`) VALUES (100000001, 235563, 1165, 68, 50, 100000005, 100000001, '50 dummy products', '2019-09-29 18:15:32', 1);");

        $faker = Faker\Factory::create();
        $data = [];
        $sizes = [];
        $products = range(1161, 1162);
        $sizes = ['1158' => range(53, 55), '1159' => range(71, 75), '1160' => array_merge(range(14, 27), [51], range(65, 67)), '1161' => [68], '1162' => array_merge([44, 45], [47, 48], [69]), '1163' => [68], '1164' => [68]];
        $locations = ['100000000', '100000001', '100000002', '100000003', '100000004', '100000005'];
        for ($i = 0; $i < 10000; ++$i) {
            $product_number = (string) $products[array_rand($products)];
            $product_sizes = $sizes[$product_number];
            $data[] = [
                'id' => (800000000 + $i), //numberBetween($min = 800000000, $max = 999999999) ###Random numbers lead to duplicate insert--> failing seeding
                'box_id' => (900000 + $i), //numberBetween($min = 100000, $max = 999000),   ###as above
                'location_id' => $locations[array_rand($locations)],
                'product_id' => $product_number,
                'size_id' => $product_sizes[array_rand($product_sizes)],
            ];
        }

        $this->table('stock')->insert($data)->save();

        $data2 = [];
        $parentids = [];
        for ($i = 0; $i < 10000; ++$i) {
            $tempdata = [
                'camp_id' => 100000000,
                'lastname' => $faker->lastName,
                'id' => 900000000 + $i,
                'date_of_birth' => $faker->dateTimeThisCentury->format('Y-m-d'),
            ];

            //#Allocate parent_id randomly
            if ($i > 1) {
                $rand_num = $faker->numberBetween($min = 0, $max = 100);
                if ($rand_num > 40) {
                    $tempdata['parent_id'] = $parentids[array_rand($parentids)];
                }
            }

            //##define gender and firstname randomly
            $gender_rand = (bool) random_int(0, 1);
            if (1 == $gender_rand) {
                $tempdata['firstname'] = $faker->firstname('male');
                $tempdata['gender'] = 'M';
            }
            if (0 == $gender_rand) {
                $tempdata['firstname'] = $faker->firstname('female');
                $tempdata['gender'] = 'F';
            }
            if (!array_key_exists('parent_id', $tempdata)) {
                array_push($parentids, $tempdata['id']);
            }

            $data2[] = $tempdata;
        }
        $this->table('people')->insert($data2)->save();
    }
}
