<?php

define('CORE',true);

session_name('core');
session_start();

# load configuration file
require_once('config.php');

# load database library
require_once('lib/database.php');

# connect to database
db_connect($settings['db_host'],$settings['db_user'],$settings['db_pass'],$settings['db_database']);

# get settings from settings table
$result = db_query('SELECT code, value FROM settings');
while($row = db_fetch($result)) $settings[$row['code']] = $row['value'];

$locale = db_row('SELECT locale FROM languages WHERE code = :lan',array('lan'=>$settings['cms_language']));
setlocale(LC_ALL, $locale);
mb_internal_encoding("UTF-8");

# load translate library
require_once('lib/translate.php');

# load Smarty (depends on database and translate)
require_once('smarty/libs/Smarty.class.php');

# load other libraries
require_once('lib/smarty.php');
require_once('lib/errorhandling.php');
require_once('lib/session.php');
require_once('lib/tools.php');
require_once('lib/mail.php');

# load CMS specific libraries
require_once('lib/form.php');
require_once('lib/list.php');
require_once('lib/formhandler.php');

if (!$login) checksession(); #check if a valid session exists; if none, redirect to loginpage
