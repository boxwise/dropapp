<?php /* Smarty version Smarty-3.1.18, created on 2017-02-19 14:20:41
         compiled from "/Users/bart/Websites/themarket/library/templates/stock_confirm.tpl" */ ?>
<?php /*%%SmartyHeaderCode:146046763058a98d99b4a5b9-53987987%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '67a95864488959dafaf30f4ecb5be7bbce77d120' => 
    array (
      0 => '/Users/bart/Websites/themarket/library/templates/stock_confirm.tpl',
      1 => 1487268130,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '146046763058a98d99b4a5b9-53987987',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'box' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_58a98d99b95be9_81104657',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58a98d99b95be9_81104657')) {function content_58a98d99b95be9_81104657($_smarty_tpl) {?><h2 class="light">New box created with ID <span class="number"><?php echo $_smarty_tpl->tpl_vars['box']->value['box_id'];?>
</span> (write this number in the top right of the box label). This box contains <span class="number"><?php echo $_smarty_tpl->tpl_vars['box']->value['items'];?>
 <?php echo $_smarty_tpl->tpl_vars['box']->value['product'];?>
</span> and is located in <span class="number"><?php echo $_smarty_tpl->tpl_vars['box']->value['location'];?>
</span></h2>

<a href="?action=stock" class="btn btn-default">Continue</a><?php }} ?>
