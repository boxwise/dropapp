<?php /* Smarty version Smarty-3.1.18, created on 2017-01-19 18:45:04
         compiled from "/home/drapeton/market/50-back/templates/cms_error.tpl" */ ?>
<?php /*%%SmartyHeaderCode:15702333205880ed108b04c8-79844736%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '680efce4effe7f5722d74d0b615bde3a9979a678' => 
    array (
      0 => '/home/drapeton/market/50-back/templates/cms_error.tpl',
      1 => 1484776980,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15702333205880ed108b04c8-79844736',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'settings' => 0,
    'translate' => 0,
    'error' => 0,
    'line' => 0,
    'file' => 0,
    'backtrace' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_5880ed108c6131_91364229',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5880ed108c6131_91364229')) {function content_5880ed108c6131_91364229($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("cms_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

	<div class="login-reset-template">
		<h1><?php echo $_smarty_tpl->tpl_vars['settings']->value['site_name'];?>
</h1>
		<div class="well-center">
			<h2><?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_error'];?>
</h2>
			<p><?php echo $_smarty_tpl->tpl_vars['error']->value;?>
</p>
			<?php if ($_SERVER['Local']) {?>
			<small>
				<p>regelnummer <?php echo $_smarty_tpl->tpl_vars['line']->value;?>
 van bestand <?php echo $_smarty_tpl->tpl_vars['file']->value;?>
</p>
				<?php echo $_smarty_tpl->tpl_vars['backtrace']->value;?>

			</small>
			<?php }?>
		</div>
	</div>
<?php echo $_smarty_tpl->getSubTemplate ("cms_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
