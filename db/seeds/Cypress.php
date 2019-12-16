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

    public function run()
    {
        //------------------- organisations
        $this->execute("INSERT INTO `organisations` (`id`, `label`, `created`, `created_by`, `deleted`, `modified`, `modified_by`) VALUES
            (100000000,'TestOrganisation','2019-07-10 08:05:56',1,NULL,NULL,NULL), 
	    (100000001,'DummyTestOrgWithBoxes','2019-09-29 08:05:56',1,NULL,NULL,NULL);");

        //------------------- camps
        $this->execute("INSERT INTO `camps` (`id`, `idcard`, `laundry`, `laundry_cyclestart`, `market`, `maxfooddrops_adult`, `maxfooddrops_child`, `modified`, `modified_by`, `name`, `organisation_id`, `schedulebreak`, `schedulebreakduration`, `schedulebreakstart`, `schedulestart`, `schedulestop`, `scheduletimeslot`, `seq`, `workshop`, `adult_age`, `bicycle`, `bicycle_closingtime`, `bicycle_closingtime_saturday`, `bicyclerenttime`, `created`, `created_by`, `currencyname`, `cyclestart`, `daystokeepdeletedpersons`, `delete_inactive_users`, `deleted`, `dropcapadult`, `dropcapchild`, `dropsperadult`, `dropsperchild`, `extraportion`, `familyidentifier`, `food`) VALUES
        	(100000000,1,0,'2019-01-01',1,25,25,'2019-09-05 13:48:34',1,'TestBase',100000000,'1','1','2019-09-05 13:00:00','2019-09-05 11:00:00','2019-09-05 17:00:00','0.5',3,0,15,0,'2019-09-05 17:30:00','2019-09-05 16:30:00',120,'2019-07-10 08:06:22',1,'Tokens','2019-01-01 00:00:00',9999,99999,NULL,99999,99999,'100','100',0,'Refugee ID',0), (100000001,1,0,'2019-01-01',1,25,25,'2019-09-05 13:48:34',1,'DummyTestBaseWithBoxes',100000001,'1','1','2019-09-05 13:00:00','2019-09-05 11:00:00','2019-09-05 17:00:00','0.5',3,0,15,0,'2019-09-05 17:30:00','2019-09-05 16:30:00',120,'2019-07-10 08:06:22',1,'Tokens','2019-01-01 00:00:00',9999,30,NULL,99999,99999,'100','100',0,'Refugee ID',0);");

        //------------------- cms_functions_camps
        $this->execute('INSERT INTO `cms_functions_camps` (`cms_functions_id`, `camps_id`) VALUES
        	(91,100000000),
        	(158,100000000),
        	(87,100000000),
        	(118,100000000),
        	(110,100000000),
        	(111,100000000),
        	(92,100000000),
        	(90,100000000),
        	(112,100000000),
        	(96,100000000),
        	(117,100000000),
        	(102,100000000),
        	(67,100000000),
			(115,100000000);');

        //------------------- cms_usergroups
        $this->execute("INSERT INTO `cms_usergroups` (`id`, `label`, `created`, `created_by`, `modified`, `modified_by`, `organisation_id`, `userlevel`, `allow_laundry_startcycle`, `allow_laundry_block`, `allow_borrow_adddelete`, `deleted`) VALUES
        	(100000000,'TestUserGroup_User','2019-07-10 08:06:53',1,NULL,NULL,100000000,3,0,0,0,NULL),
        	(100000001,'TestUserGroup_Coordinator','2019-07-10 08:07:15',1,NULL,NULL,100000000,2,0,0,0,NULL),
        	(100000002,'TestUserGroup_Admin','2019-07-10 08:07:44',1,NULL,NULL,100000000,1,0,0,0,NULL),
			(100000003,'TestUserGroup_NoPermissions','2019-07-10 08:06:53',1,NULL,NULL,100000000,3,0,0,0,NULL);");

        //------------------- cms_usergroups_functions
        $this->execute('INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES
        	(158,100000002),
        	(90,100000002),
        	(87,100000002),
        	(102,100000002),
        	(91,100000002),
        	(111,100000002),
        	(112,100000002),
        	(92,100000002),
        	(118,100000002),
        	(67,100000002),
        	(117,100000002),
        	(96,100000002),
        	(110,100000002),
        	(43,100000002),
        	(90,100000000),
        	(87,100000000),
        	(102,100000000),
        	(91,100000000),
        	(112,100000000),
        	(118,100000000),
        	(117,100000000),
        	(96,100000000),
        	(110,100000000),
        	(158,100000001),
        	(90,100000001),
        	(87,100000001),
        	(102,100000001),
        	(91,100000001),
        	(111,100000001),
        	(112,100000001),
        	(92,100000001),
        	(118,100000001),
        	(67,100000001),
        	(117,100000001),
        	(96,100000001),
        	(110,100000001),
			(43,100000001);');

        //------------------- cms_usergroups_camps
        $this->execute('INSERT INTO `cms_usergroups_camps` (`camp_id`, `cms_usergroups_id`) VALUES
        	(100000000,100000000),
        	(100000000,100000001),
        	(100000000,100000002),
			(100000000,100000003);');

        //------------------- cms_users
        $this->execute("INSERT INTO `cms_users` (`id`, `pass`, `naam`, `organisation_id`, `email`, `is_admin`, `lastlogin`, `lastaction`, `created`, `created_by`, `modified`, `modified_by`, `resetpassword`, `language`, `deleted`, `cms_usergroups_id`, `valid_firstday`, `valid_lastday`) VALUES
        	(100000000,'bb1f40afbf093afa5b9b343625ed08bd','BrowserTestUser_Admin',NULL,'admin@admin.co',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','2019-07-10 08:10:40',1,NULL,NULL,NULL,2,'0000-00-00 00:00:00',100000002,'0000-00-00','0000-00-00'),
        	(100000001,'bb1f40afbf093afa5b9b343625ed08bd','BrowserTestUser_Coordinator',NULL,'coordinator@coordinator.co',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','2019-07-10 08:11:08',1,NULL,NULL,NULL,2,'0000-00-00 00:00:00',100000001,'0000-00-00','0000-00-00'),
        	(100000002,'bb1f40afbf093afa5b9b343625ed08bd','BrowserTestUser_User',NULL,'user@user.co',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','2019-07-10 08:11:31',1,NULL,NULL,NULL,2,'0000-00-00 00:00:00',100000000,'0000-00-00','0000-00-00'),
        	(100000003,'bb1f40afbf093afa5b9b343625ed08bd','NotActived',NULL,'notactivated@notactivated.com',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','2019-07-10 08:14:35',1,NULL,NULL,NULL,2,'0000-00-00 00:00:00',100000000,'2019-07-20','2019-07-31'),
        	(100000004,'bb1f40afbf093afa5b9b343625ed08bd','Expired User',NULL,'expired@expired.co',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','2019-07-10 08:14:58',1,NULL,NULL,NULL,2,'0000-00-00 00:00:00',100000000,'2019-07-01','2019-07-09'),
        	(100000005,'bb1f40afbf093afa5b9b343625ed08bd','Deleted User',NULL,'deleted@deleted.co.deleted.100000005',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','2019-07-10 08:15:42',1,'2019-07-10 08:15:50',1,NULL,2,'2019-07-10 08:15:50',100000000,'0000-00-00','0000-00-00'),
        	(100000006,'bb1f40afbf093afa5b9b343625ed08bd','BrowserTestUser_UserWithNoPermissions',NULL,'noPermissions@noPermissions.co',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','2019-07-10 08:11:31',1,NULL,NULL,NULL,2,'0000-00-00 00:00:00',100000003,'0000-00-00','0000-00-00'),
        	(100000007,'1c962d7d2e90e539d64bca1be5257724','BrowserTestUser_DeactivateTest',NULL,'deactivateTest@deactivateTest.co',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','2019-07-10 08:11:31',1,NULL,NULL,NULL,2,'0000-00-00 00:00:00',100000000,'0000-00-00','0000-00-00'),
        	(100000008,'bb1f40afbf093afa5b9b343625ed08bd','BrowserTestUser_Pending',NULL,'pending@pending.co',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','2019-07-10 08:11:31',1,NULL,NULL,NULL,2,'0000-00-00 00:00:00',100000000,'2022-12-20','2023-12-20'),
            (100000009,'bb1f40afbf093afa5b9b343625ed08bd','Deleted Coordinator',NULL,'deleted_coordinator@deleted.co.deleted.100000009',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','2019-07-10 08:15:42',1,'2019-07-10 08:15:50',1,NULL,2,'2019-07-10 08:15:50',100000001,'2018-05-26','2019-05-26'),
        	(100000010,'bb1f40afbf093afa5b9b343625ed08bd','Deleted Admin',NULL,'deleted_admin@deleted.co.deleted.100000010',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','2019-07-10 08:15:42',1,'2019-07-10 08:15:50',1,NULL,2,'2019-07-10 08:15:50',100000002,'0000-00-00','0000-00-00'),
            (100000011,'bb1f40afbf093afa5b9b343625ed08bd','Expired Coordinator',NULL,'expired_coordinator@expired.co',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','2019-07-10 08:14:58',1,NULL,NULL,NULL,2,'0000-00-00 00:00:00',100000001,'2017-04-11','2017-05-28'),
        	(100000012,'bb1f40afbf093afa5b9b343625ed08bd','Expired Admin',NULL,'expired_admin@expired.co',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','2019-07-10 08:14:58',1,NULL,NULL,NULL,2,'0000-00-00 00:00:00',100000002,'2017-04-11','2017-05-28');");

        //------------------- locations
        $this->execute("INSERT INTO `locations` (`id`, `label`, `camp_id`, `visible`, `container_stock`, `is_market`, `is_donated`, `is_lost`) VALUES
        	(100000000,'TestShop',100000000,0,0,1,0,0),
        	(100000001,'TestLOST',100000000,0,0,0,0,1),
        	(100000002,'TestDonated',100000000,0,0,0,1,0),
        	(100000003,'TestWarehouse',100000000,1,0,0,0,0),
			(100000004,'TestStockroom',100000000,1,1,0,0,0),
            (100000005,'TestDummyLocation',100000001,0,0,1,0,0);");

        //------------------- products
        $this->execute("INSERT INTO `products` (`id`, `name`, `category_id`, `gender_id`, `sizegroup_id`, `camp_id`, `value`, `amountneeded`, `created`, `created_by`, `modified`, `modified_by`, `maxperadult`, `maxperchild`, `stockincontainer`, `comments`, `deleted`) VALUES
        	(1158,'Jeans',2,1,5,100000000,50,3,'2019-09-05 13:54:40',1,NULL,NULL,0,0,1,'',NULL),
        	(1159,'T-Shirts',3,2,1,100000000,30,3,'2019-09-05 13:55:10',1,NULL,NULL,0,0,1,'',NULL),
        	(1160,'Trainers',5,6,9,100000000,100,3,'2019-09-05 13:55:49',1,NULL,NULL,0,0,1,'',NULL),
        	(1161,'Sleeping Bag',9,3,7,100000000,100,3,'2019-09-05 13:56:23',1,NULL,NULL,0,0,0,'',NULL),
        	(1162,'Diapers',8,9,12,100000000,0,3,'2019-09-05 13:56:46',1,NULL,NULL,0,0,0,'',NULL),
        	(1163,'Shampoo (100ml)',10,10,7,100000000,20,6,'2019-09-05 13:57:31',1,NULL,NULL,0,0,1,'',NULL),
            (1164,'Rice (1kg)',11,10,7,100000000,25,3,'2019-09-05 13:57:59',1,NULL,NULL,0,0,1,'',NULL),
            (1165,'DummyProduct',2,1,5,100000001,50,3,'2019-09-05 13:54:40',1,NULL,NULL,0,0,1,'',NULL);");

        //------------------- people
        $this->execute("INSERT INTO `people` (`id`,`firstname`,`lastname`,`camp_id`,`container`,`date_of_birth`,`created`, `approvalsigned`, `signaturefield`, `date_of_signature`, `deleted`) VALUES
        	(100000001,'User', 'WithoutTokens',100000000,'001','1980-07-10','2019-09-02',1, NULL,'2019-09-02', '0000-00-00 00:00:00'),
        	(100000002, 'Conor', 'McGregor',100000000,'002','1980-07-10','2019-09-02',1,NULL,'2019-09-02','0000-00-00 00:00:00'),
        	(100000003, 'Garry', 'Tonon',100000000,'003','1985-07-10','2019-09-01',1,NULL,'2019-09-02','0000-00-00 00:00:00'),
			(100000004, 'Kron', 'Gracie',100000000,'004','1978-07-10','2019-09-02',1,NULL,'2019-09-02','0000-00-00 00:00:00'),
            (100000005, 'User', 'WithoutApproval',100000000,'004','1978-07-10','2019-09-02',0,NULL,'0000-00-00 00:00:00', '0000-00-00 00:00:00'), 
            (100000006, 'DeactivatedBeneficiary', 'DeactivatedBeneficiary',100000000,'004','1978-07-10','2019-09-02',1,NULL,'2019-09-02', '2019-10-25 11:01:30')");

        //------------------- transactions
        $this->execute("INSERT INTO `transactions` (`id`,`people_id`,`product_id`,`size_id`,`count`,`drops`,`transaction_date`, `user_id`) VALUES
        	(100000000, 100000002, 1158, 1, 0 , 2147483647 ,'2019-09-02', 1),
        	(100000001, 100000003, 1161, 1, 0 , 2147483647 ,'2019-09-02', 1),
			(100000002, 100000004, 1162, 1, 0 , 2147483647 ,'2019-09-02', 1)");

        //------------------- qr
        $this->execute("INSERT INTO `qr` (`id`, `code`) VALUES
        	(100000000,'093f65e080a295f8076b1c5722a46aa2'),
			(100000001,'44f683a84163b3523afe57c2e008bc8c'),
            (100000002,'5a5ea04157ce4d020f65c3dd950f4fa3'),
            (100000003,'5c829d1bf278615670dceeb9b3919ed2'),
            (100000004,'4b382363fa161c111fa9ad2b335ceacd'),
         	(100000005,'b1cf83ae73adfce0d14dbe81b53cb96b');");

        //------------------- stock
        $this->execute("INSERT INTO `stock` (`id`, `box_id`, `product_id`, `size_id`,`items`,`location_id`,`qr_id`,`comments`,`created`,`created_by`) VALUES
			(100000000, 328765, 1163, 68, 50, 100000002, 100000000, 'Cypress seed test box', '2015-01-01 11:15:32', 1),
            (100000001, 235563, 1165, 68, 50, 100000005, 100000001, '50 dummy products', '2019-09-29 18:15:32', 1);");
    }
}
