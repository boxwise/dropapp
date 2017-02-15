<?php /* Smarty version Smarty-3.1.18, created on 2017-02-08 13:40:15
         compiled from "/Users/maartenhunink/Sites/themarket/50-back/templates/cms_list_text.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1837239085589b039f75cda5-61904212%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5b068c2129b59967af429efcb5dd6605067dd6cb' => 
    array (
      0 => '/Users/maartenhunink/Sites/themarket/50-back/templates/cms_list_text.tpl',
      1 => 1486415168,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1837239085589b039f75cda5-61904212',
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
  'unifunc' => 'content_589b039f801d35_07521267',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_589b039f801d35_07521267')) {function content_589b039f801d35_07521267($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_truncate')) include '/Users/maartenhunink/Sites/themarket/50-back/smarty/libs/plugins/modifier.truncate.php';
?><?php if ($_smarty_tpl->tpl_vars['listdata']->value[$_smarty_tpl->tpl_vars['column']->value['field']]['breakall']) {?><span class="breakall"><?php }?>
<?php echo smarty_modifier_truncate(strip_tags($_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['column']->value['field']]));?>

<?php if ($_smarty_tpl->tpl_vars['listdata']->value[$_smarty_tpl->tpl_vars['column']->value['field']]['breakall']) {?></span><?php }?>
<?php }} ?>
