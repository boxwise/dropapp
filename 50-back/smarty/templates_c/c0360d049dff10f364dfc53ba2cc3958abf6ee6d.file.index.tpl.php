<?php /* Smarty version Smarty-3.1.18, created on 2014-07-08 14:13:25
         compiled from "./templates/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:33293403953bbe065a06757-33195891%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c0360d049dff10f364dfc53ba2cc3958abf6ee6d' => 
    array (
      0 => './templates/index.tpl',
      1 => 1404729058,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '33293403953bbe065a06757-33195891',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'page' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_53bbe065a90100_78090390',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53bbe065a90100_78090390')) {function content_53bbe065a90100_78090390($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<?php ob_start();?><?php if ($_smarty_tpl->tpl_vars['page']->value['subtemplate']) {?><?php echo (string)$_smarty_tpl->tpl_vars['page']->value['subtemplate'];?><?php } else { ?><?php echo "page";?><?php }?><?php $_tmp1=ob_get_clean();?><?php echo $_smarty_tpl->getSubTemplate ($_tmp1.".tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<?php }} ?>
