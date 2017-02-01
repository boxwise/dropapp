<?php /* Smarty version Smarty-3.1.18, created on 2016-12-20 17:52:19
         compiled from "/home/deb5672n12/domains/maartenhunink.com/public_html/drops/flip/templates/cms_form_food.tpl" */ ?>
<?php /*%%SmartyHeaderCode:16186656205858fd3794c968-25408937%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5d93ae582da7b6ec7297646b7fa8052d76274b93' => 
    array (
      0 => '/home/deb5672n12/domains/maartenhunink.com/public_html/drops/flip/templates/cms_form_food.tpl',
      1 => 1482252728,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16186656205858fd3794c968-25408937',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_5858fd37af0663_54528134',
  'variables' => 
  array (
    'element' => 0,
    'data' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5858fd37af0663_54528134')) {function content_5858fd37af0663_54528134($_smarty_tpl) {?>	<div class="col-md-6 form-group<?php if ($_smarty_tpl->tpl_vars['element']->value['hidden']) {?> hidden<?php }?>" id="div_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
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
	 			min="0" max="<?php echo $_smarty_tpl->tpl_vars['data']->value['available'][$_smarty_tpl->tpl_vars['element']->value['field']];?>
">
	 		<small class="light"><?php echo $_smarty_tpl->tpl_vars['data']->value['available'][$_smarty_tpl->tpl_vars['element']->value['field']];?>
 of <?php echo $_smarty_tpl->tpl_vars['data']->value['maxamount'][$_smarty_tpl->tpl_vars['element']->value['field']];?>
 available</small>
		</div>


	</div>
<?php }} ?>
