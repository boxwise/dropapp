<?php /* Smarty version Smarty-3.1.18, created on 2017-02-19 13:13:24
         compiled from "/Users/bart/Websites/themarket/library/templates/mobile_start.tpl" */ ?>
<?php /*%%SmartyHeaderCode:13986396358a5d927ab16b4-04056207%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ce08221060c4526002ab8142c361f48ffd6553e2' => 
    array (
      0 => '/Users/bart/Websites/themarket/library/templates/mobile_start.tpl',
      1 => 1487501200,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '13986396358a5d927ab16b4-04056207',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_58a5d927af0567_31727820',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58a5d927af0567_31727820')) {function content_58a5d927af0567_31727820($_smarty_tpl) {?>	<h2 class="page-header">Search for a box</h2>
	<form method="get" action="">
		<div class="form-group">
			<input class="form-control" type="number" name="findbox" pattern="\d*" placeholder="Enter Box ID" value="" required autofocus>
		</div>
		<input type="submit" class="btn" value="Search">
	</form>
<?php if (!$_SESSION['camp']['require_qr']) {?>
	<hr />
	<h2 class="page-header">Create a new box</h2>
	<a class="btn" href="?newbox=1">New box</a>
<?php }?><?php }} ?>
