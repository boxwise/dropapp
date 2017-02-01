<?php /* Smarty version Smarty-3.1.18, created on 2016-09-21 15:20:25
         compiled from "./templates/cases_overview.tpl" */ ?>
<?php /*%%SmartyHeaderCode:191189587657e28919316665-51166450%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '48c48c67cf5f33c7fd16cf2754c156edcae2e18f' => 
    array (
      0 => './templates/cases_overview.tpl',
      1 => 1474464016,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '191189587657e28919316665-51166450',
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
  'unifunc' => 'content_57e289193e5ed1_03288402',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57e289193e5ed1_03288402')) {function content_57e289193e5ed1_03288402($_smarty_tpl) {?><section class="cases-overview">	
	<div class="cases-overview-cases">
		<div class="container">			
		<?php  $_smarty_tpl->tpl_vars['category'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['category']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['categories']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['category']->key => $_smarty_tpl->tpl_vars['category']->value) {
$_smarty_tpl->tpl_vars['category']->_loop = true;
?>
			<div class="cases-overview-header"><h4><?php echo $_smarty_tpl->tpl_vars['category']->value['title'];?>
</h4></div>
			<div class="row multi-columns-row">
				<?php  $_smarty_tpl->tpl_vars['case'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['case']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['category']->value['cases']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['case']->key => $_smarty_tpl->tpl_vars['case']->value) {
$_smarty_tpl->tpl_vars['case']->_loop = true;
?>
					<?php echo $_smarty_tpl->getSubTemplate ("cases_overview_case.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

				<?php } ?>
			</div>			
		<?php } ?>	
		</div>
	</div>
</section>
<?php }} ?>
