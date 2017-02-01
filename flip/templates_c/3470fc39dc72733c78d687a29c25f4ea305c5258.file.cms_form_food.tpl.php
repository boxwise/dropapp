<?php /* Smarty version Smarty-3.1.18, created on 2016-12-20 18:30:08
         compiled from "./templates/cms_form_food.tpl" */ ?>
<?php /*%%SmartyHeaderCode:48025858558499da817a147-46270759%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3470fc39dc72733c78d687a29c25f4ea305c5258' => 
    array (
      0 => './templates/cms_form_food.tpl',
      1 => 1482249581,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '48025858558499da817a147-46270759',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_58499da830bf03_60802213',
  'variables' => 
  array (
    'element' => 0,
    'data' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58499da830bf03_60802213')) {function content_58499da830bf03_60802213($_smarty_tpl) {?>	<div class="col-md-6 form-group<?php if ($_smarty_tpl->tpl_vars['element']->value['hidden']) {?> hidden<?php }?>" id="div_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
">
		<input type="hidden" name="__field_amount[<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
]" value="text <?php if ($_smarty_tpl->tpl_vars['element']->value['format']!='') {?><?php echo $_smarty_tpl->tpl_vars['element']->value['format'];?>
<?php }?> <?php if ($_smarty_tpl->tpl_vars['element']->value['readonly']) {?>readonly<?php }?>">
		<label for="field_amount[<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
]" class="control-label col-sm-4 label-two-layers">
			<?php echo $_smarty_tpl->tpl_vars['element']->value['label'];?>
 
			<small class="light">(<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['name'][$_smarty_tpl->tpl_vars['element']->value['field']], ENT_QUOTES, 'UTF-8', true);?>
)</small>
		</label>


		<div class="col-sm-4 input-element input-element-small">
	 		<input type="number" id="field_amount[<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
]" name="field_amount[<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
]" 
	 			class="form-control" 
	 			value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['available'][$_smarty_tpl->tpl_vars['element']->value['field']], ENT_QUOTES, 'UTF-8', true);?>
"
	 			max="<?php echo $_smarty_tpl->tpl_vars['data']->value['available'][$_smarty_tpl->tpl_vars['element']->value['field']];?>
">
	 		<small class="light"><?php echo $_smarty_tpl->tpl_vars['data']->value['available'][$_smarty_tpl->tpl_vars['element']->value['field']];?>
 of <?php echo $_smarty_tpl->tpl_vars['data']->value['maxamount'][$_smarty_tpl->tpl_vars['element']->value['field']];?>
 available</small>
		</div>


	</div>
<?php }} ?>
