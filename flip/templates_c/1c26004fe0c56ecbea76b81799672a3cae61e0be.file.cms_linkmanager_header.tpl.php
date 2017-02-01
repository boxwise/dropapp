<?php /* Smarty version Smarty-3.1.18, created on 2015-08-18 11:54:51
         compiled from "/var/www/vhosts/global.zinnebeeld.nl/httpdocs/50-back/templates/cms_linkmanager_header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:925254762556dd1c3e4a770-12466436%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1c26004fe0c56ecbea76b81799672a3cae61e0be' => 
    array (
      0 => '/var/www/vhosts/global.zinnebeeld.nl/httpdocs/50-back/templates/cms_linkmanager_header.tpl',
      1 => 1439565357,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '925254762556dd1c3e4a770-12466436',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_556dd1c3e747d7_93128106',
  'variables' => 
  array (
    'settings' => 0,
    'pagetitle' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556dd1c3e747d7_93128106')) {function content_556dd1c3e747d7_93128106($_smarty_tpl) {?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $_smarty_tpl->tpl_vars['settings']->value['site_name'];?>
<?php if ($_smarty_tpl->tpl_vars['pagetitle']->value) {?> - <?php echo $_smarty_tpl->tpl_vars['pagetitle']->value;?>
<?php }?></title>

    <!-- Bootstrap -->
    <link href="/global/css/bootstrap.min.css" rel="stylesheet">
    <link href="/global/css/bootstrap-datetimepicker.min.css" rel="stylesheet">    
    <link href="/global/css/font-awesome.min.css" rel="stylesheet">    
    <link href="/global/css/style.css" rel="stylesheet">    
    <link href="/global/css/linkmanager.css" rel="stylesheet">        

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
	<script language="javascript">		
		function mySubmit(sUrl) {
			top.tinymce.activeEditor.windowManager.getParams().oninsert(sUrl);
			top.tinymce.activeEditor.windowManager.close();
		}		
	</script>    

    <link rel="shortcut icon" href="/global/img/favicon.ico">

  </head>
  <body>
  
<?php }} ?>
