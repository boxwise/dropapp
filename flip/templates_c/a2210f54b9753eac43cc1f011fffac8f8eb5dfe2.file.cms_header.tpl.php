<?php /* Smarty version Smarty-3.1.18, created on 2017-01-02 20:35:29
         compiled from "/home/deb5672n12/domains/maartenhunink.com/public_html/drops/50-back/templates/cms_header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:11226566215810bfd7b229d6-93588360%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a2210f54b9753eac43cc1f011fffac8f8eb5dfe2' => 
    array (
      0 => '/home/deb5672n12/domains/maartenhunink.com/public_html/drops/50-back/templates/cms_header.tpl',
      1 => 1483382113,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11226566215810bfd7b229d6-93588360',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_5810bfd7b3f263_88229628',
  'variables' => 
  array (
    'lan' => 0,
    'title' => 0,
    'translate' => 0,
    'action' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5810bfd7b3f263_88229628')) {function content_5810bfd7b3f263_88229628($_smarty_tpl) {?><!DOCTYPE html>
<html <?php if ($_smarty_tpl->tpl_vars['lan']->value=='ar') {?>dir="rtl"<?php }?> lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php if ($_smarty_tpl->tpl_vars['title']->value) {?><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
 - <?php }?><?php echo $_smarty_tpl->tpl_vars['translate']->value['site_name'];?>
</title>

    <!-- Bootstrap -->
    <link href="/global/css/css.php" rel="stylesheet">
    <link href="/flip/lib/custom.css" rel="stylesheet">    
	<link href="/flip/lib/print.css" rel="stylesheet" media="print">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

	<link rel="apple-touch-icon" sizes="180x180" href="/flip/assets/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" href="/flip/assets/images/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/flip/assets/images/favicon-16x16.png" sizes="16x16">
    
  </head>

  <body class="<?php echo $_smarty_tpl->tpl_vars['action']->value;?>
">
<?php }} ?>
