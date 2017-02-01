<?php /* Smarty version Smarty-3.1.18, created on 2017-01-24 23:14:41
         compiled from "/home/drapeton/market/50-back/templates/cms_form_ajax.tpl" */ ?>
<?php /*%%SmartyHeaderCode:14691336155887d1d18d1e11-87968822%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '679df478fe5f920d4a92080b43ca11ef3577d392' => 
    array (
      0 => '/home/drapeton/market/50-back/templates/cms_form_ajax.tpl',
      1 => 1484776980,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14691336155887d1d18d1e11-87968822',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'formelements' => 0,
    'element' => 0,
    'tabs' => 0,
    'key' => 0,
    'value' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_5887d1d18f4691_09635839',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5887d1d18f4691_09635839')) {function content_5887d1d18f4691_09635839($_smarty_tpl) {?><?php  $_smarty_tpl->tpl_vars['element'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['element']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['formelements']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['element']->key => $_smarty_tpl->tpl_vars['element']->value) {
$_smarty_tpl->tpl_vars['element']->_loop = true;
?>
	<?php if (!$_smarty_tpl->tpl_vars['element']->value['tab']&&!$_smarty_tpl->tpl_vars['element']->value['aside']) {?><?php echo $_smarty_tpl->getSubTemplate ("cms_form_".((string)$_smarty_tpl->tpl_vars['element']->value['type']).".tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }?>
<?php } ?>	

<?php if ($_smarty_tpl->tpl_vars['tabs']->value) {?>
	<?php if (count($_smarty_tpl->tpl_vars['tabs']->value)>1) {?>
		<ul class="nav nav-tabs">
			<?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['tabs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['value']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value) {
$_smarty_tpl->tpl_vars['value']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['value']->key;
 $_smarty_tpl->tpl_vars['value']->index++;
 $_smarty_tpl->tpl_vars['value']->first = $_smarty_tpl->tpl_vars['value']->index === 0;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']["tabs"]['first'] = $_smarty_tpl->tpl_vars['value']->first;
?>
			<li <?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['tabs']['first']) {?>class="active"<?php }?>><a href="#tab_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" data-toggle="tab"><?php echo $_smarty_tpl->tpl_vars['value']->value;?>
</a></li>
			<?php } ?>
		</ul>
	<?php }?>
	
	<div class="tab-content">
		<?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['tabs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['value']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value) {
$_smarty_tpl->tpl_vars['value']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['value']->key;
 $_smarty_tpl->tpl_vars['value']->index++;
 $_smarty_tpl->tpl_vars['value']->first = $_smarty_tpl->tpl_vars['value']->index === 0;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']["tabcontent"]['first'] = $_smarty_tpl->tpl_vars['value']->first;
?>
		<div class="tab-pane fade <?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['tabcontent']['first']) {?>in active<?php }?>" id="tab_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
">
		
			<?php  $_smarty_tpl->tpl_vars['element'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['element']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['formelements']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['element']->key => $_smarty_tpl->tpl_vars['element']->value) {
$_smarty_tpl->tpl_vars['element']->_loop = true;
?>
				<?php if ($_smarty_tpl->tpl_vars['element']->value['tab']&&$_smarty_tpl->tpl_vars['element']->value['tab']==$_smarty_tpl->tpl_vars['key']->value&&!$_smarty_tpl->tpl_vars['element']->value['aside']) {?><?php echo $_smarty_tpl->getSubTemplate ("cms_form_".((string)$_smarty_tpl->tpl_vars['element']->value['type']).".tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }?>
			<?php } ?>	
			
		</div>
		<?php } ?>
	</div>
<?php }?>
<?php }} ?>
