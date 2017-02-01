<?php /* Smarty version Smarty-3.1.18, created on 2015-06-02 15:13:47
         compiled from "./templates/people_overview.tpl" */ ?>
<?php /*%%SmartyHeaderCode:82877396556dac0bc89c14-76654339%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3e9155ead409a3a65952899f423b9464c2c8aa68' => 
    array (
      0 => './templates/people_overview.tpl',
      1 => 1433248475,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '82877396556dac0bc89c14-76654339',
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
  'unifunc' => 'content_556dac0bca8bb7_39993097',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556dac0bca8bb7_39993097')) {function content_556dac0bca8bb7_39993097($_smarty_tpl) {?><section class="people-overview">	
	<div class="people-overview-persons">
		<div class="container">			
		<?php  $_smarty_tpl->tpl_vars['category'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['category']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['categories']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['category']->key => $_smarty_tpl->tpl_vars['category']->value) {
$_smarty_tpl->tpl_vars['category']->_loop = true;
?>
			<div class="people-overview-header"><h4><?php echo $_smarty_tpl->tpl_vars['category']->value['title'];?>
</h4></div>
			<div class="row multi-columns-row">
				<?php  $_smarty_tpl->tpl_vars['person'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['person']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['category']->value['people']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['person']->key => $_smarty_tpl->tpl_vars['person']->value) {
$_smarty_tpl->tpl_vars['person']->_loop = true;
?>
					<?php echo $_smarty_tpl->getSubTemplate ("people_overview_person.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

				<?php } ?>
			</div>			
		<?php } ?>	
		</div>
	</div>
</section>

<?php }} ?>
