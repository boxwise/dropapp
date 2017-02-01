<?php /* Smarty version Smarty-3.1.18, created on 2015-08-25 17:33:07
         compiled from "/var/www/vhosts/global.zinnebeeld.nl/httpdocs/50-back/templates/cms_404.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1926496796556efa6b45e650-63303999%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0743fdb3cf839752b5b62eaddcad9898dbf5900c' => 
    array (
      0 => '/var/www/vhosts/global.zinnebeeld.nl/httpdocs/50-back/templates/cms_404.tpl',
      1 => 1439565355,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1926496796556efa6b45e650-63303999',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_556efa6b4bc728_43725318',
  'variables' => 
  array (
    'settings' => 0,
    'translate' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556efa6b4bc728_43725318')) {function content_556efa6b4bc728_43725318($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("cms_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

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
