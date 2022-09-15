<?php

use Phinx\Seed\AbstractSeed;

class DemoUsers extends AbstractSeed
{
    public function getDependencies()
    {
        return [
            'Minimal',
        ];
    }

    public function run()
    {
        // Generated from a mysql data dump
        // using https://regexr.com and the 'list view'
        // with the regex:
        // /(INSERT INTO (.*))/g
        // and replacement pattern:
        // // $this->execute("$1");\n

        // Faker library
        // https://github.com/fzaninotto/Faker
        $faker = Faker\Factory::create();
        // to make the seed reproducible
        $faker->seed(2);

        //------------------- organisations
        $this->execute("INSERT INTO `organisations` (`id`, `label`, `deleted`) VALUES
			(1,'BoxAid',NULL),
			(2,'BoxCare',NULL);");

        //------------------- camps
        $this->execute("INSERT INTO `camps` (`id`,`idcard`,`market`,`name`,`organisation_id`,`schedulebreak`,`schedulebreakduration`,`schedulebreakstart`,`schedulestart`,`schedulestop`,`scheduletimeslot`,`seq`,`adult_age`,`currencyname`,`cyclestart`,`daystokeepdeletedpersons`,`delete_inactive_users`,`deleted`,`dropcapadult`,`dropcapchild`,`dropsperadult`,`dropsperchild`,`extraportion`,`familyidentifier`,`resettokens`) VALUES 
			(1,0,1,'Lesvos',1,'0','1','2019-08-27 13:00:00','2019-08-27 11:00:00','2019-08-27 17:00:00','0.5',1,15,'Tokens','2019-01-01 00:00:00',9999,8,NULL,99999,99999,'100','100',0,'Refugee / Case ID',0),
			(2,0,0,'Thessaloniki',2,'0','1','13:00','9:00','15:00','0.25',2,15,'points','2019-11-05 09:13:11',9999,9999,NULL,99999,99999,'100','50',0,'Family ID',1),
			(3,0,1,'Samos',2,'0','1.5','2019-10-14 13:30:00','2019-10-14 10:30:00','2019-10-14 17:00:00','0.5',4,0,'Tokens','2019-01-06 22:18:10',60,9999,NULL,500,300,'150','130',1,'Household',0);");

        //------------------- cms_functions_camps
        $this->execute('INSERT INTO `cms_functions_camps` (`cms_functions_id`, `camps_id`) VALUES
			(67,1),
			(87,1),
			(90,1),
			(92,1),
			(96,1),
			(102,1),
			(110,1),
			(111,1),
			(112,1),
			(115,1),
			(118,1),
			(130,1),
			(145,1),
			(146,1),
			(158,1),
			(160,1),
			(162,1),
			(67,2),
			(90,2),
			(110,2),
			(112,2),
			(115,2),
			(160,2),
			(67,3),
			(87,3),
			(92,3),
			(96,3),
			(102,3),
			(110,3),
			(111,3),
			(115,3),
			(118,3),
			(130,3),
			(158,3),
			(162,3);');

        //------------------- cms_usergroups
        $this->execute("INSERT INTO `cms_usergroups` (`id`, `label`, `organisation_id`, `userlevel`) VALUES
			(1,'Head of Operations',1,1),
			(2,'Coordinator',1,2),
			(3,'WH Volunteer',1,3),
			(4,'Free Shop Volunteer',1,3),
			(5,'Library Volunteer',1,3),
			(6,'Volunteer',1,3),
			(10,'Head of Operations',2,1),
			(11,'Thessa Coordinator',2,2),
			(12,'Samos Coordinator',2,2),
			(13,'Thessa Volunteer',2,3),
			(14,'Samos Volunteer',2,3),
			(15,'Coordinator',2,2),
			(16,'Volunteer',2,3);");

        //------------------- cms_usergroups_camps
        $this->execute('INSERT INTO `cms_usergroups_camps` (`cms_usergroups_id`, `camp_id`) VALUES
			(1,1),
			(2,1),
			(3,1),
			(4,1),
			(5,1),
			(6,1),
			(10,2),
			(10,3),
			(11,2),
			(12,3),
			(13,2),
			(14,3),
			(15,2),
			(15,3),
			(16,2),
			(16,3);');

        //------------------- cms_usergroups_functions
        $this->execute('INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES
			(43,1),
			(67,1),
			(87,1),
			(90,1),
			(92,1),
			(96,1),
			(102,1),
			(110,1),
			(111,1),
			(112,1),
			(115,1),
			(118,1),
			(130,1),
			(145,1),
			(146,1),
			(156,1),
			(158,1),
			(160,1),
			(162,1),
			(43,2),
			(67,2),
			(87,2),
			(90,2),
			(92,2),
			(96,2),
			(102,2),
			(110,2),
			(111,2),
			(112,2),
			(115,2),
			(118,2),
			(130,2),
			(145,2),
			(146,2),
			(158,2),
			(160,2),
			(162,2),
			(90,3),
			(110,3),
			(112,3),
			(160,3),
			(87,4),
			(96,4),
			(102,4),
			(110,4),
			(118,4),
			(130,4),
			(158,4),
			(102,5),
			(118,5),
			(145,5),
			(146,5),
			(87,6),
			(90,6),
			(96,6),
			(102,6),
			(110,6),
			(112,6),
			(118,6),
			(130,6),
			(145,6),
			(146,6),
			(158,6),
			(160,6),
			(43,10),
			(67,10),
			(87,10),
			(90,10),
			(92,10),
			(96,10),
			(102,10),
			(110,10),
			(111,10),
			(112,10),
			(115,10),
			(118,10),
			(130,10),
			(156,10),
			(158,10),
			(160,10),
			(162,10),
			(43,11),
			(67,11),
			(90,11),
			(110,11),
			(112,11),
			(115,11),
			(160,11),
			(43,12),
			(67,12),
			(87,12),
			(92,12),
			(96,12),
			(102,12),
			(110,12),
			(111,12),
			(118,12),
			(130,12),
			(158,12),
			(162,12),
			(90,13),
			(110,13),
			(112,13),
			(160,13),
			(87,14),
			(96,14),
			(102,14),
			(110,14),
			(118,14),
			(130,14),
			(158,14),
			(43,15),
			(67,15),
			(87,15),
			(90,15),
			(92,15),
			(96,15),
			(102,15),
			(110,15),
			(111,15),
			(112,15),
			(115,15),
			(118,15),
			(130,15),
			(158,15),
			(160,15),
			(162,15),
			(87,16),
			(90,16),
			(96,16),
			(102,16),
			(110,16),
			(112,16),
			(118,16),
			(130,16),
			(158,16),
			(160,16);');

        //------------------- cms_users
        $this->execute("INSERT INTO `cms_users` (`id`, `pass`, `naam`, `email`, `is_admin`, `resetpassword`, `language`, `deleted`, `cms_usergroups_id`, `valid_firstday`, `valid_lastday`) VALUES
            (2,'bf13b44feae208fc808b1d6b2266edb7','Jane Doe','jane.doe@boxaid.co',0,NULL,2,'0000-00-00 00:00:00',1,'0000-00-00','0000-00-00'),
            (3,'bf13b44feae208fc808b1d6b2266edb7','Joe Doe','joe.doe@boxaid.co',0,NULL,2,'0000-00-00 00:00:00',2,'0000-00-00','0000-00-00'),
			(4,'bf13b44feae208fc808b1d6b2266edb7','Volunteer','stagingenv_volunteer@boxtribute.org',0,NULL,2,'0000-00-00 00:00:00',3,'0000-00-00','0000-00-00'),
			(5,'bf13b44feae208fc808b1d6b2266edb7','Coordinator','stagingenv_coordinator@boxtribute.org',0,NULL,2,'0000-00-00 00:00:00',2,'0000-00-00','0000-00-00'),
			(6,'bf13b44feae208fc808b1d6b2266edb7','Head of Operations','stagingenv_headofops@boxtribute.org',0,NULL,2,'0000-00-00 00:00:00',1,'0000-00-00','0000-00-00'),
			(7,'bf13b44feae208fc808b1d6b2266edb7','Dev Volunteer','dev_volunteer@boxaid.org',0,NULL,2,'0000-00-00 00:00:00',3,'0000-00-00','0000-00-00'),
			(8,'bf13b44feae208fc808b1d6b2266edb7','Dev Coordinator','dev_coordinator@boxaid.org',0,NULL,2,'0000-00-00 00:00:00',2,'0000-00-00','0000-00-00'),
			(9,'bf13b44feae208fc808b1d6b2266edb7','Dev Head of Operations','dev_headofops@boxaid.org',0,NULL,2,'0000-00-00 00:00:00',1,'0000-00-00','0000-00-00'),
			(10,'bf13b44feae208fc808b1d6b2266edb7','Jane Doe','jane.doe@boxcare.co',0,NULL,2,'0000-00-00 00:00:00',10,'0000-00-00','0000-00-00'),
            (11,'bf13b44feae208fc808b1d6b2266edb7','Joe Doe','joe.doe@boxcare.co',0,NULL,2,'0000-00-00 00:00:00',11,'0000-00-00','0000-00-00'),
            (12,'bf13b44feae208fc808b1d6b2266edb7','Sam Sample','sam.sample@boxcare.co',0,NULL,2,'0000-00-00 00:00:00',12,'0000-00-00','0000-00-00'),
            (15,'bf13b44feae208fc808b1d6b2266edb7','Joe Bloggs','joe.bloggs@boxcare.co',0,NULL,2,'0000-00-00 00:00:00',15,'0000-00-00','0000-00-00'),
			(16,'bf13b44feae208fc808b1d6b2266edb7','Dev Volunteer','dev_volunteer@boxcare.org',0,NULL,2,'0000-00-00 00:00:00',16,'0000-00-00','0000-00-00'),
			(17,'bf13b44feae208fc808b1d6b2266edb7','Dev Coordinator','dev_coordinator@boxcare.org',0,NULL,2,'0000-00-00 00:00:00',15,'0000-00-00','0000-00-00'),
			(18,'bf13b44feae208fc808b1d6b2266edb7','Dev Head of Operations','dev_headofops@boxcare.org',0,NULL,2,'0000-00-00 00:00:00',10,'0000-00-00','0000-00-00');");

        // user generation fixed for Auth0
        // Uncomment if you want to generate new users.
        // $users = [];
        // for ($i = 20; $i <= 120; ++$i) {
        //     $tempdata = [
        //         'id' => $i,
        //         'cms_usergroups_id' => $faker->randomElement([3, 4, 5, 6, 13, 14, 16]),
        //         'email' => $faker->unique()->email,
        //         'language' => 2,
        //         'naam' => $faker->name,
        //         'pass' => 'bf13b44feae208fc808b1d6b2266edb7',
        //     ];

        //     // set valid dates for 70 per cent of users
        //     $rand_num = $faker->numberBetween($min = 0, $max = 100);
        //     if ($rand_num < 71) {
        //         $tempdata['valid_firstday'] = $faker->dateTimeBetween($startDate = '-350 days', $endDate = '+250 days', $timezone = 'Europe/Athens')->format('Y-m-d H:i:s');
        //         $tempdata['valid_lastday'] = $faker->dateTimeBetween($startDate = $tempdata['valid_firstday'], $endDate = '+255 days', $timezone = 'Europe/Athens')->format('Y-m-d H:i:s');
        //     } else {
        //         $tempdata['valid_firstday'] = '0000-00-00 00:00:00';
        //         $tempdata['valid_lastday'] = '0000-00-00 00:00:00';
        //     }

        //     // deactivate 5 per cent of the users
        //     $rand_num = $faker->numberBetween($min = 0, $max = 100);
        //     if ($rand_num < 5) {
        //         $tempdata['deleted'] = $faker->dateTimeThisYear($max = 'now', $timezone = 'Europe/Athens')->format('Y-m-d H:i:s');
        //         $tempdata['email'] = $tempdata['email'].'.deleted.'.$i;
        //     }
        //     $users[] = $tempdata;
        // }
        // $this->table('cms_users')->insert($users)->save();
        // fix password, email and name of the users for auth0
        // $this->execute("
        // 	INSERT INTO
        // 		`cms_users` (`id`, `pass`, `naam`,`email`)
        // 	VALUES
        // 		(20,'bf13b44feae208fc808b1d6b2266edb7','Kallie Johnson','owaters@hotmail.com'),
        // 		(21,'bf13b44feae208fc808b1d6b2266edb7','Jamey Paucek','predovic.dena@hilpert.biz'),
        // 		(22,'bf13b44feae208fc808b1d6b2266edb7','Dr. Guillermo Cormier PhD','goodwin.mollie@gmail.com'),
        // 		(23,'bf13b44feae208fc808b1d6b2266edb7','Dr. Herman Fadel I','anya18@gmail.com'),
        // 		(24,'bf13b44feae208fc808b1d6b2266edb7','Dr. Price Kuphal','chanelle53@yahoo.com'),
        // 		(25,'bf13b44feae208fc808b1d6b2266edb7','Dr. Percy Bruen DVM','spencer.matilde@stehr.org'),
        // 		(26,'bf13b44feae208fc808b1d6b2266edb7','Dr. Janice Schultz Jr.','nyah.konopelski@kohler.com'),
        // 		(27,'bf13b44feae208fc808b1d6b2266edb7','Mr. Sim Abernathy Jr.','norma02@gaylord.info'),
        // 		(28,'bf13b44feae208fc808b1d6b2266edb7','Ms. Lea Kovacek II','denis48@hotmail.com'),
        // 		(29,'bf13b44feae208fc808b1d6b2266edb7','Molly Jones','kaelyn88@gmail.com'),
        // 		(30,'bf13b44feae208fc808b1d6b2266edb7','Lesley Johnston','adell.hermann@schmeler.info'),
        // 		(31,'bf13b44feae208fc808b1d6b2266edb7','Irving Murray DVM','kunze.helene@dare.biz'),
        // 		(32,'bf13b44feae208fc808b1d6b2266edb7','Declan Mayert','aliya33@hotmail.com'),
        // 		(33,'bf13b44feae208fc808b1d6b2266edb7','Lavonne Collier','nsipes@cartwright.com'),
        // 		(34,'bf13b44feae208fc808b1d6b2266edb7','Miss Patience O\\'Conner','littel.dashawn@schaefer.org'),
        // 		(35,'bf13b44feae208fc808b1d6b2266edb7','Mrs. Mireille Hodkiewicz PhD','clind@yahoo.com'),
        // 		(36,'bf13b44feae208fc808b1d6b2266edb7','Miss Ashtyn Stehr','elroy.reilly@rolfson.org'),
        // 		(37,'bf13b44feae208fc808b1d6b2266edb7','Darion Schaefer','carrie.kuhlman@turcotte.net'),
        // 		(38,'bf13b44feae208fc808b1d6b2266edb7','Miss Norene Hartmann MD','sporer.winfield@hotmail.com'),
        // 		(39,'bf13b44feae208fc808b1d6b2266edb7','Alejandra Davis','unique35@mueller.info.deleted.39'),
        // 		(40,'bf13b44feae208fc808b1d6b2266edb7','Prof. Sydni Baumbach','oconnell.keshawn@bosco.org'),
        // 		(41,'bf13b44feae208fc808b1d6b2266edb7','Kamryn Quigley III','carter.ilene@hotmail.com'),
        // 		(42,'bf13b44feae208fc808b1d6b2266edb7','Prof. Joaquin Hand Jr.','swaniawski.jarret@kihn.com'),
        // 		(43,'bf13b44feae208fc808b1d6b2266edb7','Mr. Vern Romaguera MD','sanford.daryl@hotmail.com'),
        // 		(44,'bf13b44feae208fc808b1d6b2266edb7','Megane Kozey','jerde.rosie@quitzon.com'),
        // 		(45,'bf13b44feae208fc808b1d6b2266edb7','Edison Bartoletti','xlind@herman.biz'),
        // 		(46,'bf13b44feae208fc808b1d6b2266edb7','Jaydon Hoeger','bartholome55@hotmail.com'),
        // 		(47,'bf13b44feae208fc808b1d6b2266edb7','Emma Eichmann','ilene.lind@gmail.com'),
        // 		(48,'bf13b44feae208fc808b1d6b2266edb7','Quinton Boyle','dkeebler@johnson.biz'),
        // 		(49,'bf13b44feae208fc808b1d6b2266edb7','Desmond Renner','cassandre07@damore.biz.deleted.49'),
        // 		(50,'bf13b44feae208fc808b1d6b2266edb7','Dusty Schultz','claudia.frami@gmail.com.deleted.50'),
        // 		(51,'bf13b44feae208fc808b1d6b2266edb7','Coby Bergstrom PhD','hallie62@gmail.com.deleted.51'),
        // 		(52,'bf13b44feae208fc808b1d6b2266edb7','Dion Hauck','alessandra78@schulist.com'),
        // 		(53,'bf13b44feae208fc808b1d6b2266edb7','Reymundo Crona','gflatley@yahoo.com'),
        // 		(54,'bf13b44feae208fc808b1d6b2266edb7','Prof. Nannie Schmeler DDS','lisette.quitzon@rice.com'),
        // 		(55,'bf13b44feae208fc808b1d6b2266edb7','Mr. Kirk Brown','shyanne63@hotmail.com'),
        // 		(56,'bf13b44feae208fc808b1d6b2266edb7','Prof. Reginald Schaefer IV','shanna90@hotmail.com'),
        // 		(57,'bf13b44feae208fc808b1d6b2266edb7','Ms. Carlie Balistreri MD','keshaun01@yahoo.com'),
        // 		(58,'bf13b44feae208fc808b1d6b2266edb7','Dr. Winnifred Nolan','emmanuelle64@auer.org'),
        // 		(59,'bf13b44feae208fc808b1d6b2266edb7','Domenic Gutmann','leffler.kattie@schiller.com'),
        // 		(60,'bf13b44feae208fc808b1d6b2266edb7','Marianna Boyer','awelch@hotmail.com'),
        // 		(61,'bf13b44feae208fc808b1d6b2266edb7','Brycen Jenkins','gislason.buster@considine.info'),
        // 		(62,'bf13b44feae208fc808b1d6b2266edb7','Fausto Doyle','ullrich.horace@gmail.com'),
        // 		(63,'bf13b44feae208fc808b1d6b2266edb7','Damian Kassulke','abdiel.pagac@gmail.com'),
        // 		(64,'bf13b44feae208fc808b1d6b2266edb7','Edison Wolff I','marie.wuckert@gmail.com'),
        // 		(65,'bf13b44feae208fc808b1d6b2266edb7','Leila Olson','lillie.dubuque@okon.info'),
        // 		(66,'bf13b44feae208fc808b1d6b2266edb7','Dr. Sigurd Herman DVM','morissette.helene@gmail.com'),
        // 		(67,'bf13b44feae208fc808b1d6b2266edb7','Prof. Gage Boyer','funk.tanya@hotmail.com'),
        // 		(68,'bf13b44feae208fc808b1d6b2266edb7','Jewel Murray','balistreri.precious@nienow.net'),
        // 		(69,'bf13b44feae208fc808b1d6b2266edb7','Mr. Neil Cruickshank','nvandervort@emard.com'),
        // 		(70,'bf13b44feae208fc808b1d6b2266edb7','Ms. Janice Donnelly','shickle@blanda.com'),
        // 		(71,'bf13b44feae208fc808b1d6b2266edb7','Marian Kuhlman DVM','swaniawski.alanis@yahoo.com'),
        // 		(72,'bf13b44feae208fc808b1d6b2266edb7','Perry Kirlin','miller.dena@yahoo.com'),
        // 		(73,'bf13b44feae208fc808b1d6b2266edb7','Gerhard Feeney','lily.klocko@yahoo.com'),
        // 		(74,'bf13b44feae208fc808b1d6b2266edb7','Dr. Osborne Erdman PhD','shields.jordan@green.com'),
        // 		(75,'bf13b44feae208fc808b1d6b2266edb7','Fannie Welch','durgan.delphia@hotmail.com'),
        // 		(76,'bf13b44feae208fc808b1d6b2266edb7','Ruthie Smith','sister.jacobs@vandervort.org'),
        // 		(77,'bf13b44feae208fc808b1d6b2266edb7','Fern Barrows','roob.elnora@yahoo.com'),
        // 		(78,'bf13b44feae208fc808b1d6b2266edb7','Alana Lang','price.laila@koelpin.com'),
        // 		(79,'bf13b44feae208fc808b1d6b2266edb7','Rowland Bayer','kirsten72@luettgen.net'),
        // 		(80,'bf13b44feae208fc808b1d6b2266edb7','Kelton Grant','cparisian@kemmer.net'),
        // 		(81,'bf13b44feae208fc808b1d6b2266edb7','Nannie Erdman','shane63@gmail.com'),
        // 		(82,'bf13b44feae208fc808b1d6b2266edb7','Kirstin O\\'Kon','reece.conroy@ruecker.org'),
        // 		(83,'bf13b44feae208fc808b1d6b2266edb7','Ms. Rosie Bashirian Jr.','mayert.rhett@dare.com'),
        // 		(84,'bf13b44feae208fc808b1d6b2266edb7','Dorthy Hartmann','alvina09@gmail.com'),
        // 		(85,'bf13b44feae208fc808b1d6b2266edb7','Mr. Sanford Leffler IV','gkulas@hotmail.com'),
        // 		(86,'bf13b44feae208fc808b1d6b2266edb7','Armani Wuckert','dheidenreich@kub.com'),
        // 		(87,'bf13b44feae208fc808b1d6b2266edb7','Elenora Pouros PhD','egerhold@fadel.com'),
        // 		(88,'bf13b44feae208fc808b1d6b2266edb7','Elijah Daugherty','price.danielle@beier.org'),
        // 		(89,'bf13b44feae208fc808b1d6b2266edb7','Earl Botsford','heller.tre@skiles.info'),
        // 		(90,'bf13b44feae208fc808b1d6b2266edb7','Kayleigh Lemke','ydaugherty@yahoo.com'),
        // 		(91,'bf13b44feae208fc808b1d6b2266edb7','Adella Hagenes','mstehr@yahoo.com'),
        // 		(92,'bf13b44feae208fc808b1d6b2266edb7','Keyon Fritsch','mathias.reichel@hotmail.com'),
        // 		(93,'bf13b44feae208fc808b1d6b2266edb7','Eladio Ryan','ltrantow@hotmail.com'),
        // 		(94,'bf13b44feae208fc808b1d6b2266edb7','Amari Wuckert','goldner.camren@gmail.com'),
        // 		(95,'bf13b44feae208fc808b1d6b2266edb7','Prof. Carmel Zboncak','dbrown@gmail.com'),
        // 		(96,'bf13b44feae208fc808b1d6b2266edb7','Lucie Ledner IV','angelita.will@hotmail.com'),
        // 		(97,'bf13b44feae208fc808b1d6b2266edb7','Geraldine Smith','diana54@rippin.info'),
        // 		(98,'bf13b44feae208fc808b1d6b2266edb7','Ms. Ona Shields','lucinda.huels@gutkowski.com.deleted.98'),
        // 		(99,'bf13b44feae208fc808b1d6b2266edb7','Abigayle Wyman','esther.strosin@sauer.com'),
        // 		(100,'bf13b44feae208fc808b1d6b2266edb7','Taya Osinski','jamaal68@hotmail.com'),
        // 		(101,'bf13b44feae208fc808b1d6b2266edb7','Edison Gusikowski','mattie.nader@jerde.com'),
        // 		(102,'bf13b44feae208fc808b1d6b2266edb7','Dr. Letha Douglas','vjohns@lemke.biz'),
        // 		(103,'bf13b44feae208fc808b1d6b2266edb7','Giovanna Hand','kavon00@mosciski.com'),
        // 		(104,'bf13b44feae208fc808b1d6b2266edb7','Baby Armstrong','erau@yahoo.com'),
        // 		(105,'bf13b44feae208fc808b1d6b2266edb7','Rebecca Ebert','champlin.shawna@stiedemann.biz'),
        // 		(106,'bf13b44feae208fc808b1d6b2266edb7','Vergie Kshlerin','walker.janis@yahoo.com'),
        // 		(107,'bf13b44feae208fc808b1d6b2266edb7','Vena Franecki','lexi.skiles@jast.com'),
        // 		(108,'bf13b44feae208fc808b1d6b2266edb7','Ms. Anastasia Stoltenberg IV','rmedhurst@hotmail.com'),
        // 		(109,'bf13b44feae208fc808b1d6b2266edb7','Dion Mayer','gislason.garland@hotmail.com.deleted.109'),
        // 		(110,'bf13b44feae208fc808b1d6b2266edb7','Mr. Skye Hessel Jr.','rdeckow@schmitt.info'),
        // 		(111,'bf13b44feae208fc808b1d6b2266edb7','Vincenzo Bartoletti V','felicity.berge@hotmail.com'),
        // 		(112,'bf13b44feae208fc808b1d6b2266edb7','Ona Predovic','tlangworth@yahoo.com'),
        // 		(113,'bf13b44feae208fc808b1d6b2266edb7','Ms. Itzel Reynolds III','kuhic.reuben@yahoo.com'),
        // 		(114,'bf13b44feae208fc808b1d6b2266edb7','Letha Hand','cara07@lindgren.net'),
        // 		(115,'bf13b44feae208fc808b1d6b2266edb7','Mr. Einar Okuneva','jklocko@gmail.com'),
        // 		(116,'bf13b44feae208fc808b1d6b2266edb7','Mikayla Anderson','meffertz@ebert.com'),
        // 		(117,'bf13b44feae208fc808b1d6b2266edb7','Ward Hintz','fstanton@hotmail.com.deleted.117'),
        // 		(118,'bf13b44feae208fc808b1d6b2266edb7','Ms. Leatha Bechtelar IV','elbert.strosin@yahoo.com'),
        // 		(119,'bf13b44feae208fc808b1d6b2266edb7','Mylene Spinka','qhowe@bahringer.com'),
        // 		(120,'bf13b44feae208fc808b1d6b2266edb7','Janae Fay DVM','doris14@schaden.com')
        // 	ON DUPLICATE KEY UPDATE id = Values(id),naam=Values(naam),email=Values(email),pass=Values(pass);");
    }
}