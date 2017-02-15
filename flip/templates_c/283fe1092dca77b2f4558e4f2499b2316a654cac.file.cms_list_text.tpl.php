<?php /* Smarty version Smarty-3.1.18, created on 2017-02-15 15:48:07
         compiled from "/Users/maartenhunink/Sites/themarket/library/templates/cms_list_text.tpl" */ ?>
<?php /*%%SmartyHeaderCode:99372458458a45c17955112-49815162%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '283fe1092dca77b2f4558e4f2499b2316a654cac' => 
    array (
      0 => '/Users/maartenhunink/Sites/themarket/library/templates/cms_list_text.tpl',
      1 => 1487166423,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '99372458458a45c17955112-49815162',
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
  'unifunc' => 'content_58a45c17974854_68316016',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58a45c17974854_68316016')) {function content_58a45c17974854_68316016($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_truncate')) include '/Users/maartenhunink/Sites/themarket/library/smarty/libs/plugins/modifier.truncate.php';
?><?php if ($_smarty_tpl->tpl_vars['listdata']->value[$_smarty_tpl->tpl_vars['column']->value['field']]['breakall']) {?><span class="breakall"><?php }?>
<?php echo smarty_modifier_truncate(strip_tags($_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['column']->value['field']]));?>

<?php if ($_smarty_tpl->tpl_vars['listdata']->value[$_smarty_tpl->tpl_vars['column']->value['field']]['breakall']) {?></span><?php }?>
<?php }} ?>
