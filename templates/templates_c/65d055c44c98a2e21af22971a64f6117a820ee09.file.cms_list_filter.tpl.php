<?php /* Smarty version Smarty-3.1.18, created on 2017-02-15 22:44:03
         compiled from "/Users/bart/Websites/themarket/library/templates/cms_list_filter.tpl" */ ?>
<?php /*%%SmartyHeaderCode:127045937058a4bd939ba4f5-93791519%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '65d055c44c98a2e21af22971a64f6117a820ee09' => 
    array (
      0 => '/Users/bart/Websites/themarket/library/templates/cms_list_filter.tpl',
      1 => 1486395480,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '127045937058a4bd939ba4f5-93791519',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'listconfig' => 0,
    'key' => 0,
    'option' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_58a4bd939fc485_54848825',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58a4bd939fc485_54848825')) {function content_58a4bd939fc485_54848825($_smarty_tpl) {?><?php if ($_smarty_tpl->tpl_vars['listconfig']->value['filter']) {?>
	<li>
		<div class="btn-group">
			<div type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
				<?php if ($_smarty_tpl->tpl_vars['listconfig']->value['filtervalue']) {?>
					<?php echo $_smarty_tpl->tpl_vars['listconfig']->value['filter']['options'][$_smarty_tpl->tpl_vars['listconfig']->value['filtervalue']];?>

				<?php } else { ?>
					<?php echo $_smarty_tpl->tpl_vars['listconfig']->value['filter']['label'];?>

				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['listconfig']->value['filtervalue']) {?>
					<a class="fa fa-times form-control-feedback" href="?action=<?php echo $_smarty_tpl->tpl_vars['listconfig']->value['origin'];?>
&resetfilter=true"></a>
				<?php } else { ?>
					<span class="caret"></span>
				<?php }?>
			</div>
			<ul class="dropdown-menu pull-right" role="menu">
				<?php  $_smarty_tpl->tpl_vars['option'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['option']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['listconfig']->value['filter']['options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['option']->key => $_smarty_tpl->tpl_vars['option']->value) {
$_smarty_tpl->tpl_vars['option']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['option']->key;
?>
					<li><a href="?action=<?php echo $_smarty_tpl->tpl_vars['listconfig']->value['origin'];?>
&filter=<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['option']->value;?>
</a></li></li> 
				<?php } ?>
			</ul>
		</div>
	</li>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['listconfig']->value['filter2']) {?>
	<li>
		<div class="btn-group">
			<div type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
				<?php if ($_smarty_tpl->tpl_vars['listconfig']->value['filtervalue2']) {?>
					<?php echo $_smarty_tpl->tpl_vars['listconfig']->value['filter2']['options'][$_smarty_tpl->tpl_vars['listconfig']->value['filtervalue2']];?>

				<?php } else { ?>
					<?php echo $_smarty_tpl->tpl_vars['listconfig']->value['filter2']['label'];?>

				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['listconfig']->value['filtervalue2']) {?>
					<a class="fa fa-times form-control-feedback" href="?action=<?php echo $_smarty_tpl->tpl_vars['listconfig']->value['origin'];?>
&resetfilter2=true"></a>
				<?php } else { ?>
					<span class="caret"></span>
				<?php }?>
			</div>
			<ul class="dropdown-menu pull-right" role="menu">
				<?php  $_smarty_tpl->tpl_vars['option'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['option']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['listconfig']->value['filter2']['options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['option']->key => $_smarty_tpl->tpl_vars['option']->value) {
$_smarty_tpl->tpl_vars['option']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['option']->key;
?>
					<li><a href="?action=<?php echo $_smarty_tpl->tpl_vars['listconfig']->value['origin'];?>
&filter2=<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['option']->value;?>
</a></li></li> 
				<?php } ?>
			</ul>
		</div>
	</li>
<?php }?>
<?php }} ?>
