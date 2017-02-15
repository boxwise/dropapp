<?php /* Smarty version Smarty-3.1.18, created on 2017-02-08 12:53:04
         compiled from "/Users/maartenhunink/Sites/themarket/50-back/templates/cms_error.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2134726748589af890c438a0-83748788%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '894e494f13eba2c98263e8426d6ee115f75d5546' => 
    array (
      0 => '/Users/maartenhunink/Sites/themarket/50-back/templates/cms_error.tpl',
      1 => 1486415168,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2134726748589af890c438a0-83748788',
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
  'unifunc' => 'content_589af890cee310_76192035',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_589af890cee310_76192035')) {function content_589af890cee310_76192035($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("cms_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

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
