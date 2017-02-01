<?php /* Smarty version Smarty-3.1.18, created on 2015-08-17 10:04:12
         compiled from "/var/www/vhosts/global.zinnebeeld.nl/httpdocs/50-back/templates/cms_error.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2034899328556dac02b61ea5-15226102%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9fa54f62c8cf9654c47862351c198e03940d57b1' => 
    array (
      0 => '/var/www/vhosts/global.zinnebeeld.nl/httpdocs/50-back/templates/cms_error.tpl',
      1 => 1439565355,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2034899328556dac02b61ea5-15226102',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_556dac02be9080_36561899',
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
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556dac02be9080_36561899')) {function content_556dac02be9080_36561899($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("cms_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

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
