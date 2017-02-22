<?php /* Smarty version Smarty-3.1.18, created on 2017-02-16 17:54:21
         compiled from "/Users/bart/Websites/themarket/library/templates/cms_form_history.tpl" */ ?>
<?php /*%%SmartyHeaderCode:194503871958a5d93de2b5b1-35241307%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a29f42da7c44cbc7d6da29adc31d698ad31475bb' => 
    array (
      0 => '/Users/bart/Websites/themarket/library/templates/cms_form_history.tpl',
      1 => 1486395474,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '194503871958a5d93de2b5b1-35241307',
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
  'unifunc' => 'content_58a5d93dee56f1_47853636',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58a5d93dee56f1_47853636')) {function content_58a5d93dee56f1_47853636($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_truncate')) include '/Users/bart/Websites/themarket/library/smarty/libs/plugins/modifier.truncate.php';
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
