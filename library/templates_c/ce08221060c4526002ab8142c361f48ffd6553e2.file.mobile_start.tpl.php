<?php /* Smarty version Smarty-3.1.18, created on 2017-02-15 18:01:08
         compiled from "/Users/bart/Websites/themarket/library/templates/mobile_start.tpl" */ ?>
<?php /*%%SmartyHeaderCode:44347004058a47b443c8be9-89767138%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ce08221060c4526002ab8142c361f48ffd6553e2' => 
    array (
      0 => '/Users/bart/Websites/themarket/library/templates/mobile_start.tpl',
      1 => 1487158532,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '44347004058a47b443c8be9-89767138',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_58a47b443cf415_35014486',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58a47b443cf415_35014486')) {function content_58a47b443cf415_35014486($_smarty_tpl) {?>	<h2 class="page-header">Enter a box number</h2>
	<form method="get" action="">
		<div class="form-group">
			<input class="form-control" type="number" name="findbox" pattern="\d*" placeholder="Box Number" value="" required>
		</div>
		<input type="submit" class="btn" value="Search">
	</form>
<?php if (!$_SESSION['camp']['require_qr']) {?>
	<hr />
	<h2 class="page-header">Or create a new box</h2>
	<a class="btn" href="?newbox=1">New box</a>
<?php }?><?php }} ?>
