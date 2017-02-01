<?php /* Smarty version Smarty-3.1.18, created on 2015-08-27 14:50:12
         compiled from "cms_insertfaq_index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:96872832055df0784db73c9-59901886%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '27f39a80ff0725b1dc3d4e2bf3ede8d2b6cb1634' => 
    array (
      0 => 'cms_insertfaq_index.tpl',
      1 => 1440679552,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '96872832055df0784db73c9-59901886',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'categories' => 0,
    'category' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55df078501cfc7_45330735',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55df078501cfc7_45330735')) {function content_55df078501cfc7_45330735($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("cms_insertfaq_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>


<div id="container">
	<div class="container-fluid">
		<select name="faq_cat" id="faq_cat">
			<option value="">Kies een onderwerp...</option>
			<?php  $_smarty_tpl->tpl_vars['category'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['category']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['categories']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['category']->key => $_smarty_tpl->tpl_vars['category']->value) {
$_smarty_tpl->tpl_vars['category']->_loop = true;
?>
				<option value="faq<?php echo $_smarty_tpl->tpl_vars['category']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['category']->value['title'];?>
</option>
			<?php } ?>
		</select>
	</div>
</div>

<?php echo $_smarty_tpl->getSubTemplate ("cms_insertfaq_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
