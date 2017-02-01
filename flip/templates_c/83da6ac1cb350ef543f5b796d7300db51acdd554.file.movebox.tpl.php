<?php /* Smarty version Smarty-3.1.18, created on 2016-12-07 12:54:48
         compiled from "./templates/movebox.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2479022915847dbf39e7d21-00010170%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '83da6ac1cb350ef543f5b796d7300db51acdd554' => 
    array (
      0 => './templates/movebox.tpl',
      1 => 1481108053,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2479022915847dbf39e7d21-00010170',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_5847dbf39f6d45_17310473',
  'variables' => 
  array (
    'lan' => 0,
    'move' => 0,
    'box' => 0,
    'newlocation' => 0,
    'locations' => 0,
    'value' => 0,
    'label' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5847dbf39f6d45_17310473')) {function content_5847dbf39f6d45_17310473($_smarty_tpl) {?><!DOCTYPE html>
<html <?php if ($_smarty_tpl->tpl_vars['lan']->value=='ar') {?>dir="rtl"<?php }?> lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Drop Market Stock tool</title>

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

	<head>
		<style>
			body {
				font-family: Arial, sans-serif;
				font-size:24px;
				margin:10px;
				padding:0;
				}
			button {
				font-family: Arial, sans-serif;
				font-size:24px;
				-moz-border-radius: 10px;
				-webkit-border-radius: 10px;
				border-radius: 10px;
				border:none;
				background-color: #eee;
				width:100%;
				padding:10px;
				margin-bottom:20px;
			}
			a {
				text-decoration: none;
				color:#2383ac;
			}
		</style>
	</head>
	<body>
		<?php if ($_smarty_tpl->tpl_vars['move']->value) {?>
			<?php if ($_smarty_tpl->tpl_vars['box']->value['id']) {?>
				<p>Box <strong><?php echo $_smarty_tpl->tpl_vars['box']->value['box_id'];?>
</strong> contains <?php echo $_smarty_tpl->tpl_vars['box']->value['items'];?>
x <strong><?php echo $_smarty_tpl->tpl_vars['box']->value['product'];?>
</strong><br />is moved from <strong><?php echo $_smarty_tpl->tpl_vars['box']->value['location'];?>
</strong> to <strong><?php echo $_smarty_tpl->tpl_vars['newlocation']->value['label'];?>
</p>
			<?php } else { ?>
				<p>This box is not found in the Drop Market administration.<br /><a href="">Add box to stock</a></p>
			<?php }?>
		<?php } else { ?>
			<?php if ($_smarty_tpl->tpl_vars['box']->value['id']) {?>
			<p>Box <strong><?php echo $_smarty_tpl->tpl_vars['box']->value['box_id'];?>
</strong> contains <?php echo $_smarty_tpl->tpl_vars['box']->value['items'];?>
x <strong><?php echo $_smarty_tpl->tpl_vars['box']->value['product'];?>
</strong><br />Move this box from <strong><?php echo $_smarty_tpl->tpl_vars['box']->value['location'];?>
</strong> to:</p>
				<?php  $_smarty_tpl->tpl_vars['label'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['label']->_loop = false;
 $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['locations']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['label']->key => $_smarty_tpl->tpl_vars['label']->value) {
$_smarty_tpl->tpl_vars['label']->_loop = true;
 $_smarty_tpl->tpl_vars['value']->value = $_smarty_tpl->tpl_vars['label']->key;
?>
				<button><a href="scan.php?move=<?php echo $_smarty_tpl->tpl_vars['box']->value['id'];?>
&location=<?php echo $_smarty_tpl->tpl_vars['value']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['label']->value;?>
</a></button><br />
				<?php } ?>
			<?php } else { ?>
				<p>This box is not found in the Drop Market administration.<br /><a href="">Add box to stock</a></p>
			<?php }?>
		<?php }?>
	</body>
</html><?php }} ?>
