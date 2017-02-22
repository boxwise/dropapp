<?php /* Smarty version Smarty-3.1.18, created on 2017-02-15 18:41:48
         compiled from "/Users/bart/Websites/themarket/library/templates/cms_error.tpl" */ ?>
<?php /*%%SmartyHeaderCode:50505977258a492dc477e55-48330192%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fa3669789b3d42933da4c0c88241da3f9b8e6913' => 
    array (
      0 => '/Users/bart/Websites/themarket/library/templates/cms_error.tpl',
      1 => 1486395471,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '50505977258a492dc477e55-48330192',
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
  'unifunc' => 'content_58a492dc4d0ea3_30231962',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58a492dc4d0ea3_30231962')) {function content_58a492dc4d0ea3_30231962($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("cms_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

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
