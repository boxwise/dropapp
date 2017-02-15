<?php /* Smarty version Smarty-3.1.18, created on 2017-02-08 13:35:51
         compiled from "/Users/maartenhunink/Sites/themarket/50-back/templates/cms_form_created.tpl" */ ?>
<?php /*%%SmartyHeaderCode:975293042589b0297db9965-06637417%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0a3fa2c7f3ba913709ff42cb0df8fac2a6a009bf' => 
    array (
      0 => '/Users/maartenhunink/Sites/themarket/50-back/templates/cms_form_created.tpl',
      1 => 1486415168,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '975293042589b0297db9965-06637417',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'data' => 0,
    'translate' => 0,
    'settings' => 0,
    'table' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_589b0297e126b1_86407044',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_589b0297e126b1_86407044')) {function content_589b0297e126b1_86407044($_smarty_tpl) {?><div class="created light small">
<?php if ($_smarty_tpl->tpl_vars['data']->value['created']) {?>
	<?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_form_createdby'];?>
 <?php if ($_smarty_tpl->tpl_vars['data']->value['created_by']) {?><?php echo $_smarty_tpl->tpl_vars['data']->value['created_by'];?>
<?php } else { ?><em><?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_form_createdunknown'];?>
</em><?php }?><br /><?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_form_ondate'];?>
 <?php echo $_smarty_tpl->tpl_vars['data']->value['created'];?>
 <br /><br />
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['data']->value['modified']) {?>
	<?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_form_modifiedby'];?>
 <?php if ($_smarty_tpl->tpl_vars['data']->value['modified_by']) {?><?php echo $_smarty_tpl->tpl_vars['data']->value['modified_by'];?>
<?php } else { ?><em><?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_form_createdunknown'];?>
</em><?php }?><br /><?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_form_ondate'];?>
 <?php echo $_smarty_tpl->tpl_vars['data']->value['modified'];?>
 
	<?php if ($_smarty_tpl->tpl_vars['settings']->value['showhistory']) {?><br /> <br /><i class="fa fa-history"></i> <a href="/flip/ajax.php?file=history&table=<?php if ($_smarty_tpl->tpl_vars['table']->value) {?><?php echo $_smarty_tpl->tpl_vars['table']->value;?>
<?php } else { ?><?php echo $_GET['action'];?>
<?php }?>&id=<?php echo $_smarty_tpl->tpl_vars['data']->value['id'];?>
" class="fancybox" data-fancybox-type="ajax"><?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_form_view_modified'];?>
</a><?php }?>
<?php }?>
</div><?php }} ?>
