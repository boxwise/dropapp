<?php /* Smarty version Smarty-3.1.18, created on 2017-02-15 18:28:47
         compiled from "/Users/bart/Websites/themarket/library/templates/cms_404.tpl" */ ?>
<?php /*%%SmartyHeaderCode:213620349158a48fcfa7fb69-17357152%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '991e8c3916d7f61e01e8415f72e6e0c127aa6939' => 
    array (
      0 => '/Users/bart/Websites/themarket/library/templates/cms_404.tpl',
      1 => 1486395471,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '213620349158a48fcfa7fb69-17357152',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'settings' => 0,
    'translate' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_58a48fcfabc106_51630745',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58a48fcfabc106_51630745')) {function content_58a48fcfabc106_51630745($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("cms_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

	<div class="login-reset-template">
		<h1><?php echo $_smarty_tpl->tpl_vars['settings']->value['site_name'];?>
</h1>
		<div class="well-center">
			<h2><?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_error'];?>
</h2>
			<p><?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_error404'];?>
</p>
		</div>
	</div>
<?php echo $_smarty_tpl->getSubTemplate ("cms_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
