<?php /* Smarty version Smarty-3.1.18, created on 2017-02-15 14:06:57
         compiled from "./templates/mobile.tpl" */ ?>
<?php /*%%SmartyHeaderCode:179474154589b84859b4b78-61199911%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '10832ac0048bd6a624c28113702a94c78fbce0e8' => 
    array (
      0 => './templates/mobile.tpl',
      1 => 1487160315,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '179474154589b84859b4b78-61199911',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_589b8485aac506_76750931',
  'variables' => 
  array (
    'lan' => 0,
    'settings' => 0,
    'camps' => 0,
    'c' => 0,
    'currentcamp' => 0,
    'data' => 0,
    'include' => 0,
    'translate' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_589b8485aac506_76750931')) {function content_589b8485aac506_76750931($_smarty_tpl) {?><!DOCTYPE html>
<html <?php if ($_smarty_tpl->tpl_vars['lan']->value=='ar') {?>dir="rtl"<?php }?> lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Drop Market - Mobile</title>
		
		<link href="<?php echo $_smarty_tpl->tpl_vars['settings']->value['rootdir'];?>
/assets/css/bootstrap.min.css" rel="stylesheet">    
		<link href="<?php echo $_smarty_tpl->tpl_vars['settings']->value['rootdir'];?>
/assets/css/select2.css" rel="stylesheet" />
		<link href="<?php echo $_smarty_tpl->tpl_vars['settings']->value['rootdir'];?>
/assets/css/select2-bootstrap.css" rel="stylesheet" />
		<link href="<?php echo $_smarty_tpl->tpl_vars['settings']->value['rootdir'];?>
/assets/css/mobile.css" rel="stylesheet">    

		<script src="<?php echo $_smarty_tpl->tpl_vars['settings']->value['rootdir'];?>
/assets/js/jquery-3.1.1.min.js"></script>
		<script src="<?php echo $_smarty_tpl->tpl_vars['settings']->value['rootdir'];?>
/assets/js/jquery.validate.min.js"></script>
		<script src="<?php echo $_smarty_tpl->tpl_vars['settings']->value['rootdir'];?>
/assets/js/bootstrap.min.js"></script>
		<script src="<?php echo $_smarty_tpl->tpl_vars['settings']->value['rootdir'];?>
/assets/js/select2.min.js"></script>
		<script src="<?php echo $_smarty_tpl->tpl_vars['settings']->value['rootdir'];?>
/assets/js/mobile.js"></script>

    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $_smarty_tpl->tpl_vars['settings']->value['rootdir'];?>
/assets/img/apple-touch-icon.png">
    <link rel="icon" type="image/png" href="<?php echo $_smarty_tpl->tpl_vars['settings']->value['rootdir'];?>
/assets/img/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="<?php echo $_smarty_tpl->tpl_vars['settings']->value['rootdir'];?>
/assets/img/favicon-16x16.png" sizes="16x16">

	</head>
	<body class="mobile">		
		<strong><a href="<?php echo $_smarty_tpl->tpl_vars['settings']->value['rootdir'];?>
/flip/scan.php"><?php echo $_smarty_tpl->tpl_vars['settings']->value['site_name'];?>
</a></strong>
		<?php if ($_SESSION['user']) {?>
	 		<?php if (count($_smarty_tpl->tpl_vars['camps']->value)>1) {?>
	 			<div id="campselect">
					<select name="campselect" dir="rtl">
					 		<?php  $_smarty_tpl->tpl_vars['c'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['c']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['camps']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['c']->key => $_smarty_tpl->tpl_vars['c']->value) {
$_smarty_tpl->tpl_vars['c']->_loop = true;
?>
				 				<option value="?barcode=<?php echo $_GET['barcode'];?>
&camp=<?php echo $_smarty_tpl->tpl_vars['c']->value['id'];?>
" <?php if ($_smarty_tpl->tpl_vars['c']->value['id']==$_smarty_tpl->tpl_vars['currentcamp']->value['id']) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['c']->value['name'];?>
</option>
					 		<?php } ?>
					</select>
	 			</div>
	 		<?php } elseif (count($_smarty_tpl->tpl_vars['camps']->value)==1) {?>
	 				<?php echo $_smarty_tpl->tpl_vars['camps']->value[0]['name'];?>

	 		<?php }?>
		<?php }?>
		<hr>
		<?php if ($_smarty_tpl->tpl_vars['data']->value['message']) {?><div class="message <?php if ($_smarty_tpl->tpl_vars['data']->value['warning']) {?>warning<?php }?>"><?php echo $_smarty_tpl->tpl_vars['data']->value['message'];?>
</div><?php }?>
		<?php if ($_smarty_tpl->tpl_vars['include']->value) {?><?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['include']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }?>
		<?php if ($_SESSION['user']) {?>
			<footer>
				<a href="?logout=1"><?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_menu_logout'];?>
</a>
			</footer>
		<?php }?>
		<div id="loading"><div class="cp-spinner cp-round"></div></div>
	</body>
</html><?php }} ?>
