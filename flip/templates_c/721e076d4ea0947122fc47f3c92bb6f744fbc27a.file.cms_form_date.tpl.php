<?php /* Smarty version Smarty-3.1.18, created on 2017-02-08 13:39:54
         compiled from "/Users/maartenhunink/Sites/themarket/50-back/templates/cms_form_date.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1118129611589b038a3409f1-02881891%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '721e076d4ea0947122fc47f3c92bb6f744fbc27a' => 
    array (
      0 => '/Users/maartenhunink/Sites/themarket/50-back/templates/cms_form_date.tpl',
      1 => 1486415168,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1118129611589b038a3409f1-02881891',
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
  'unifunc' => 'content_589b038a45a718_82408882',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_589b038a45a718_82408882')) {function content_589b038a45a718_82408882($_smarty_tpl) {?>	<div class="form-group<?php if ($_smarty_tpl->tpl_vars['element']->value['hidden']) {?> hidden<?php }?>" id="div_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
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
