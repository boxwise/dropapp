<?php /* Smarty version Smarty-3.1.18, created on 2015-08-27 14:50:13
         compiled from "cms_insertfaq_header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:85052167455df078502d9a5-51375700%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c55515a524896fe26cf6aa27d1c82e3e6314ab24' => 
    array (
      0 => 'cms_insertfaq_header.tpl',
      1 => 1440679552,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '85052167455df078502d9a5-51375700',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'settings' => 0,
    'pagetitle' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55df0785057469_45041794',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55df0785057469_45041794')) {function content_55df0785057469_45041794($_smarty_tpl) {?><!DOCTYPE html>
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
    <link href="/global/css/insertfaq.css" rel="stylesheet">

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
