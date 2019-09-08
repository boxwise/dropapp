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
        $this->execute("INSERT INTO `organisations` (`id`, `label`, `created`, `created_by`, `deleted`, `modified`, `modified_by`) VALUES (100000000,'TestOrganisation','2019-07-10 08:05:56',1,NULL,NULL,NULL);");
        $this->execute("INSERT INTO `camps` (`id`, `idcard`, `laundry`, `laundry_cyclestart`, `market`, `maxfooddrops_adult`, `maxfooddrops_child`, `modified`, `modified_by`, `name`, `organisation_id`, `schedulebreak`, `schedulebreakduration`, `schedulebreakstart`, `schedulestart`, `schedulestop`, `scheduletimeslot`, `seq`, `workshop`, `adult_age`, `bicycle`, `bicycle_closingtime`, `bicycle_closingtime_saturday`, `bicyclerenttime`, `created`, `created_by`, `currencyname`, `cyclestart`, `daystokeepdeletedpersons`, `delete_inactive_users`, `deleted`, `dropcapadult`, `dropcapchild`, `dropsperadult`, `dropsperchild`, `extraportion`, `familyidentifier`, `food`) VALUES (100000000,1,0,'2019-01-01',1,25,25,'2019-09-05 13:48:34',1,'TestBase',100000000,'1','1','2019-09-05 13:00:00','2019-09-05 11:00:00','2019-09-05 17:00:00','0.5',3,0,15,0,'2019-09-05 17:30:00','2019-09-05 16:30:00',120,'2019-07-10 08:06:22',1,'Tokens','2019-01-01 00:00:00',9999,30,NULL,99999,99999,'100','100',0,'Refugee ID',0);");
        $this->execute('INSERT INTO `cms_functions_camps` (`cms_functions_id`, `camps_id`) VALUES (91,100000000),(158,100000000),(87,100000000),(118,100000000),(110,100000000),(111,100000000),(92,100000000),(90,100000000),(112,100000000),(96,100000000),(117,100000000),(102,100000000),(67,100000000),(115,100000000);');
        $this->execute("INSERT INTO `cms_usergroups` (`id`, `label`, `created`, `created_by`, `modified`, `modified_by`, `organisation_id`, `userlevel`, `allow_laundry_startcycle`, `allow_laundry_block`, `allow_borrow_adddelete`, `deleted`) VALUES (100000000,'TestUserGroup_User','2019-07-10 08:06:53',1,NULL,NULL,100000000,3,0,0,0,NULL),(100000001,'TestUserGroup_Coordinator','2019-07-10 08:07:15',1,NULL,NULL,100000000,2,0,0,0,NULL),(100000002,'TestUserGroup_Admin','2019-07-10 08:07:44',1,NULL,NULL,100000000,1,0,0,0,NULL);");
        $this->execute('INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES (158,100000002),(90,100000002),(87,100000002),(102,100000002),(91,100000002),(111,100000002),(112,100000002),(92,100000002),(118,100000002),(67,100000002),(117,100000002),(96,100000002),(110,100000002),(43,100000002),(90,100000000),(87,100000000),(102,100000000),(91,100000000),(112,100000000),(118,100000000),(117,100000000),(96,100000000),(110,100000000),(158,100000001),(90,100000001),(87,100000001),(102,100000001),(91,100000001),(111,100000001),(112,100000001),(92,100000001),(118,100000001),(67,100000001),(117,100000001),(96,100000001),(110,100000001),(43,100000001);');
        $this->execute('INSERT INTO `cms_usergroups_camps` (`camp_id`, `cms_usergroups_id`) VALUES (100000000,100000000),(100000000,100000001),(100000000,100000002);');
        $this->execute("INSERT INTO `cms_users` (`id`, `pass`, `naam`, `organisation_id`, `email`, `is_admin`, `lastlogin`, `lastaction`, `created`, `created_by`, `modified`, `modified_by`, `resetpassword`, `language`, `deleted`, `cms_usergroups_id`, `valid_firstday`, `valid_lastday`) VALUES (100000000,'bb1f40afbf093afa5b9b343625ed08bd','BrowserTestUser_Admin',NULL,'admin@admin.co',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','2019-07-10 08:10:40',1,NULL,NULL,NULL,NULL,'0000-00-00 00:00:00',100000002,'0000-00-00','0000-00-00'),(100000001,'bb1f40afbf093afa5b9b343625ed08bd','BrowserTestUser_Coordinator',NULL,'coordinator@coordinator.co',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','2019-07-10 08:11:08',1,NULL,NULL,NULL,NULL,'0000-00-00 00:00:00',100000001,'0000-00-00','0000-00-00'),(100000002,'bb1f40afbf093afa5b9b343625ed08bd','BrowserTestUser_User',NULL,'user@user.co',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','2019-07-10 08:11:31',1,NULL,NULL,NULL,NULL,'0000-00-00 00:00:00',100000000,'0000-00-00','0000-00-00'),(100000003,'bb1f40afbf093afa5b9b343625ed08bd','NotActived',NULL,'notactivated@notactivated.com',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','2019-07-10 08:14:35',1,NULL,NULL,NULL,NULL,'0000-00-00 00:00:00',100000000,'2019-07-20','2019-07-31'),(100000004,'bb1f40afbf093afa5b9b343625ed08bd','Expired',NULL,'expired@expired.co',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','2019-07-10 08:14:58',1,NULL,NULL,NULL,NULL,'0000-00-00 00:00:00',100000000,'2019-07-01','2019-07-09'),(100000005,'bb1f40afbf093afa5b9b343625ed08bd','Deleted',NULL,'deleted@deleted.co.deleted.100000005',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','2019-07-10 08:15:42',1,'2019-07-10 08:15:50',1,NULL,NULL,'2019-07-10 08:15:50',100000000,'0000-00-00','0000-00-00');");
        $this->execute("INSERT INTO `locations` (`id`, `label`, `camp_id`, `visible`, `container_stock`, `is_market`, `is_donated`, `is_lost`) VALUES (100000000,'TestShop',100000000,0,0,1,0,0),(100000001,'TestLOST',100000000,0,0,0,0,1),(100000002,'TestDonated',100000000,0,0,0,1,0),(100000003,'TestWarehouse',100000000,1,0,0,0,0),(100000004,'TestStockroom',100000000,1,1,0,0,0);");
        $this->execute("INSERT INTO `products` (`id`, `name`, `category_id`, `gender_id`, `sizegroup_id`, `camp_id`, `value`, `amountneeded`, `created`, `created_by`, `modified`, `modified_by`, `maxperadult`, `maxperchild`, `stockincontainer`, `comments`, `deleted`) VALUES (1158,'Jeans',2,1,5,100000000,50,3,'2019-09-05 13:54:40',1,NULL,NULL,0,0,1,'',NULL),(1159,'T-Shirts',3,2,10,100000000,30,3,'2019-09-05 13:55:10',1,NULL,NULL,0,0,1,'',NULL),(1160,'Trainers',5,6,9,100000000,100,3,'2019-09-05 13:55:49',1,NULL,NULL,0,0,1,'',NULL),(1161,'Sleeping Bag',9,3,7,100000000,100,3,'2019-09-05 13:56:23',1,NULL,NULL,0,0,0,'',NULL),(1162,'Diapers',8,9,2,100000000,0,3,'2019-09-05 13:56:46',1,NULL,NULL,0,0,0,'',NULL),(1163,'Shampoo (100ml)',10,10,7,100000000,20,6,'2019-09-05 13:57:31',1,NULL,NULL,0,0,1,'',NULL),(1164,'Rice (1kg)',11,10,7,100000000,25,3,'2019-09-05 13:57:59',1,NULL,NULL,0,0,1,'',NULL);");
        $this->execute("INSERT INTO `people` (`id`,`firstname`,`lastname`,`camp_id`,`container`,`date_of_birth`,`created`) VALUES (100000001,'User', 'WithoutTokens',100000000,'001','1980-07-10','2019-09-02'), (100000002, 'Conor', 'McGregor',100000000,'002','1980-07-10','2019-09-02'), (100000003, 'Garry', 'Tonon',100000000,'003','1985-07-10','2019-09-01'), (100000004, 'Kron', 'Gracie',100000000,'004','1978-07-10','2019-09-02')");
        $this->execute("INSERT INTO `transactions` (`id`,`people_id`,`product_id`,`size_id`,`count`,`drops`,`transaction_date`, `user_id`) VALUES (1, 100000002, 0, 0, 0 , 2147483647 ,'2019-09-02', 1),(2, 100000003, 0, 0, 0 , 2147483647 ,'2019-09-02', 1), (3, 100000004, 0, 0, 0 , 2147483647 ,'2019-09-02', 1)");
    }
}
