<?php /* Smarty version Smarty-3.1.18, created on 2017-02-15 15:07:08
         compiled from "./templates/mobile_start.tpl" */ ?>
<?php /*%%SmartyHeaderCode:173839620858a31041b91a57-70521112%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '38959aa59d6257dc1191aaba7c192effaa4ee0ac' => 
    array (
      0 => './templates/mobile_start.tpl',
      1 => 1487164023,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '173839620858a31041b91a57-70521112',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_58a31041bf88b4_13269891',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58a31041bf88b4_13269891')) {function content_58a31041bf88b4_13269891($_smarty_tpl) {?>	<h2 class="page-header">Search for a box</h2>
	<form method="get" action="">
		<div class="form-group">
			<input class="form-control" type="number" name="findbox" pattern="\d*" placeholder="Enter Box ID" value="" required>
		</div>
		<input type="submit" class="btn" value="Search">
	</form>
<?php if (!$_SESSION['camp']['require_qr']) {?>
	<hr />
	<h2 class="page-header">Create a new box</h2>
	<a class="btn" href="?newbox=1">New box</a>
<?php }?><?php }} ?>
