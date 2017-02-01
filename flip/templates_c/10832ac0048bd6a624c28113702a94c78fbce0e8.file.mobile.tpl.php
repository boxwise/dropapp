<?php /* Smarty version Smarty-3.1.18, created on 2017-01-19 22:21:19
         compiled from "./templates/mobile.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1348301502587b526834aff1-67799512%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '10832ac0048bd6a624c28113702a94c78fbce0e8' => 
    array (
      0 => './templates/mobile.tpl',
      1 => 1484857129,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1348301502587b526834aff1-67799512',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_587b5268607464_00594930',
  'variables' => 
  array (
    'lan' => 0,
    'include' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_587b5268607464_00594930')) {function content_587b5268607464_00594930($_smarty_tpl) {?><!DOCTYPE html>
<html <?php if ($_smarty_tpl->tpl_vars['lan']->value=='ar') {?>dir="rtl"<?php }?> lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Drop Market Stock tool</title>
		
		<link href="/flip/lib/bootstrap.min.css" rel="stylesheet">    
		
		<script src="/flip/lib/jquery-3.1.1.min.js"></script>
		<script src="/flip/lib/jquery.validate.min.js"></script>
		<script src="/flip/lib/bootstrap.min.js"></script>

		<link href="/flip/lib/select2.min.css" rel="stylesheet" />
		<script src="/flip/lib/select2.min.js"></script>
		
		<script src="/flip/lib/mobile.js"></script>

		<link href="/flip/lib/mobile.css" rel="stylesheet">    

	</head>
	<body class="mobile">
		<?php if ($_smarty_tpl->tpl_vars['include']->value) {?><?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['include']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }?>
		<div id="loading"><div class="cp-spinner cp-round"></div></div>
	</body>
</html><?php }} ?>
