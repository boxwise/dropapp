<?php /* Smarty version Smarty-3.1.18, created on 2016-10-26 16:42:20
         compiled from "/home/deb5672n12/domains/maartenhunink.com/public_html/drops/50-back/templates/cms_list_text.tpl" */ ?>
<?php /*%%SmartyHeaderCode:4981662545810c0cc6905a9-60325215%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5621f68abe973b106b8448330b88e806c1ddda32' => 
    array (
      0 => '/home/deb5672n12/domains/maartenhunink.com/public_html/drops/50-back/templates/cms_list_text.tpl',
      1 => 1477491597,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4981662545810c0cc6905a9-60325215',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'column' => 0,
    'listdata' => 0,
    'row' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_5810c0cc6b29c6_18760279',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5810c0cc6b29c6_18760279')) {function content_5810c0cc6b29c6_18760279($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_truncate')) include '/home/deb5672n12/domains/maartenhunink.com/public_html/drops/50-back/smarty/libs/plugins/modifier.truncate.php';
?><?php if ($_smarty_tpl->tpl_vars['listdata']->value[$_smarty_tpl->tpl_vars['column']->value['field']]['breakall']) {?><span class="breakall"><?php }?>
<?php echo smarty_modifier_truncate(strip_tags($_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['column']->value['field']]));?>

<?php if ($_smarty_tpl->tpl_vars['listdata']->value[$_smarty_tpl->tpl_vars['column']->value['field']]['breakall']) {?></span><?php }?>
<?php }} ?>
