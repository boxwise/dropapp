<?php /* Smarty version Smarty-3.1.18, created on 2017-01-19 18:34:24
         compiled from "/home/drapeton/market/50-back/templates/cms_form_date.tpl" */ ?>
<?php /*%%SmartyHeaderCode:12489008045880ea90cecc73-85405637%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c0de063667dd8f579ff0aa955e95a4475faf30a2' => 
    array (
      0 => '/home/drapeton/market/50-back/templates/cms_form_date.tpl',
      1 => 1484776981,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12489008045880ea90cecc73-85405637',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'element' => 0,
    'lan' => 0,
    'data' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_5880ea90d5b4e4_50746214',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5880ea90d5b4e4_50746214')) {function content_5880ea90d5b4e4_50746214($_smarty_tpl) {?>	<div class="form-group<?php if ($_smarty_tpl->tpl_vars['element']->value['hidden']) {?> hidden<?php }?>" id="div_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
">
		<input type="hidden" name="__<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" value="date format(<?php echo $_smarty_tpl->tpl_vars['element']->value['dateformat'];?>
) <?php if ($_smarty_tpl->tpl_vars['element']->value['readonly']) {?>readonly<?php }?>">
		<label for="field_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" class="control-label col-sm-2"><?php echo $_smarty_tpl->tpl_vars['element']->value['label'];?>
</label>
		<div class="col-sm-<?php if ($_smarty_tpl->tpl_vars['element']->value['width']>0&&$_smarty_tpl->tpl_vars['element']->value['width']<11) {?><?php echo $_smarty_tpl->tpl_vars['element']->value['width'];?>
<?php } else { ?>4<?php }?> input-element">
			<div class='input-group date' data-language="<?php echo $_smarty_tpl->tpl_vars['lan']->value;?>
" data-date-format="<?php echo $_smarty_tpl->tpl_vars['element']->value['dateformat'];?>
" data-pick-date="<?php if ($_smarty_tpl->tpl_vars['element']->value['date']) {?>1<?php } else { ?>0<?php }?>" data-pick-time="<?php if ($_smarty_tpl->tpl_vars['element']->value['time']) {?>1<?php } else { ?>0<?php }?>">
				<input type="text" id="field_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" name="<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" 
					class="form-control" 
					value="<?php echo $_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['element']->value['field']];?>
" 
					<?php if ($_smarty_tpl->tpl_vars['element']->value['required']) {?>required<?php }?> 
					<?php if ($_smarty_tpl->tpl_vars['element']->value['onblur']) {?>onblur="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['element']->value['onblur'], ENT_QUOTES, 'UTF-8', true);?>
;"<?php }?>
					<?php if ($_smarty_tpl->tpl_vars['element']->value['onchange']) {?>onchange="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['element']->value['onchange'], ENT_QUOTES, 'UTF-8', true);?>
;"<?php }?>
					<?php if ($_smarty_tpl->tpl_vars['element']->value['onkeyup']) {?>onkeyup="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['element']->value['onkeyup'], ENT_QUOTES, 'UTF-8', true);?>
;"<?php }?>
				/>
				<span class="input-group-addon"><span class="fa <?php if ($_smarty_tpl->tpl_vars['element']->value['date']) {?>fa-calendar<?php } else { ?>fa-clock-o<?php }?>"></span></span>
			</div>
			<?php if ($_smarty_tpl->tpl_vars['element']->value['tooltip']) {?><?php echo $_smarty_tpl->getSubTemplate ("cms_tooltip.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }?>
		</div>
	</div>
<?php }} ?>
