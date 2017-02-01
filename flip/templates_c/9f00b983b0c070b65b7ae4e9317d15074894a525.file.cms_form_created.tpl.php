<?php /* Smarty version Smarty-3.1.18, created on 2016-12-08 16:17:46
         compiled from "/home/deb5672n12/domains/maartenhunink.com/public_html/drops/50-back/templates/cms_form_created.tpl" */ ?>
<?php /*%%SmartyHeaderCode:12504662035810c1f436b750-76651777%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9f00b983b0c070b65b7ae4e9317d15074894a525' => 
    array (
      0 => '/home/deb5672n12/domains/maartenhunink.com/public_html/drops/50-back/templates/cms_form_created.tpl',
      1 => 1481206658,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12504662035810c1f436b750-76651777',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_5810c1f43873f9_64581156',
  'variables' => 
  array (
    'data' => 0,
    'translate' => 0,
    'settings' => 0,
    'table' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5810c1f43873f9_64581156')) {function content_5810c1f43873f9_64581156($_smarty_tpl) {?><div class="created light small">
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
