<?php /* Smarty version Smarty-3.1.18, created on 2017-01-30 13:35:09
         compiled from "/home/drapeton/market/50-back/templates/cms_form_history.tpl" */ ?>
<?php /*%%SmartyHeaderCode:269332392588f32fdb7ea07-54849902%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9b232e8f0f02814ed82b5953b13358a2375a8edd' => 
    array (
      0 => '/home/drapeton/market/50-back/templates/cms_form_history.tpl',
      1 => 1484776981,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '269332392588f32fdb7ea07-54849902',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'row' => 0,
    'line' => 0,
    'translate' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_588f32fdcf82c3_30770742',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_588f32fdcf82c3_30770742')) {function content_588f32fdcf82c3_30770742($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_truncate')) include '/home/drapeton/market/50-back/smarty/libs/plugins/modifier.truncate.php';
?><div>
	<?php  $_smarty_tpl->tpl_vars['line'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['line']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['row']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['line']->key => $_smarty_tpl->tpl_vars['line']->value) {
$_smarty_tpl->tpl_vars['line']->_loop = true;
?>
	<strong><?php echo $_smarty_tpl->tpl_vars['line']->value['naam'];?>
</strong> <?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_form_ondate'];?>
 <strong><?php echo $_smarty_tpl->tpl_vars['line']->value['changedate'];?>
</strong><br />
	<div class="small">
		<span class="text-truncated"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['line']->value['changes'],300);?>

		<?php if ($_smarty_tpl->tpl_vars['line']->value['truncate']) {?> 
			<a class="text-show-original" href="#">Read more</a></span>
			<span class="hide text-original"><?php echo $_smarty_tpl->tpl_vars['line']->value['changes'];?>
<a class="text-hide-original" href="#">Read less</a></span>
		<?php } else { ?>
			</span>
		<?php }?>
	</div>
	<hr />
	<?php } ?>
</div><?php }} ?>
