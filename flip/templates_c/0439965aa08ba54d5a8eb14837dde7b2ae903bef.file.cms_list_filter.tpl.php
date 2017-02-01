<?php /* Smarty version Smarty-3.1.18, created on 2015-08-14 17:17:48
         compiled from "/var/www/vhosts/global.zinnebeeld.nl/httpdocs/50-back/templates/cms_list_filter.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1452262120559a75fc0dbd83-12675286%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0439965aa08ba54d5a8eb14837dde7b2ae903bef' => 
    array (
      0 => '/var/www/vhosts/global.zinnebeeld.nl/httpdocs/50-back/templates/cms_list_filter.tpl',
      1 => 1439565357,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1452262120559a75fc0dbd83-12675286',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_559a75fc17dbf0_51891692',
  'variables' => 
  array (
    'listconfig' => 0,
    'key' => 0,
    'option' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_559a75fc17dbf0_51891692')) {function content_559a75fc17dbf0_51891692($_smarty_tpl) {?><?php if ($_smarty_tpl->tpl_vars['listconfig']->value['filter']) {?>
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
