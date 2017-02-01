<?php /* Smarty version Smarty-3.1.18, created on 2015-09-30 10:20:00
         compiled from "./templates/product_call2action.tpl" */ ?>
<?php /*%%SmartyHeaderCode:635336005556dab94e47076-11912135%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6d17bf22bdae3d42671ce69683f132bf97e75a8a' => 
    array (
      0 => './templates/product_call2action.tpl',
      1 => 1443601200,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '635336005556dab94e47076-11912135',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_556dab94e95ae9_41905454',
  'variables' => 
  array (
    'page' => 0,
    'translate' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556dab94e95ae9_41905454')) {function content_556dab94e95ae9_41905454($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/var/www/vhosts/global.zinnebeeld.nl/httpdocs/50-back/smarty/libs/plugins/modifier.date_format.php';
?><?php if ($_smarty_tpl->tpl_vars['page']->value['buttontext']&&($_smarty_tpl->tpl_vars['page']->value['form_id']||$_smarty_tpl->tpl_vars['page']->value['external_url'])) {?>
<div class="article-content-apply">
	<?php if ($_smarty_tpl->tpl_vars['page']->value['fullybooked']) {?>
		<a href="#" class="btn btn-disabled"><?php echo $_smarty_tpl->tpl_vars['translate']->value['product_fullybooked'];?>
</a>
	<?php } elseif ($_smarty_tpl->tpl_vars['page']->value['agendadate']&&smarty_modifier_date_format($_smarty_tpl->tpl_vars['page']->value['agendadate'],"%Y-%m-%d")<smarty_modifier_date_format(time(),"%Y-%m-%d")) {?>
		<a href="#" class="btn btn-disabled"><?php echo $_smarty_tpl->tpl_vars['translate']->value['product_history'];?>
</a>
	<?php } else { ?>

			<?php if ($_smarty_tpl->tpl_vars['page']->value['external_url']) {?>
				<a href="<?php echo $_smarty_tpl->tpl_vars['page']->value['external_url'];?>
" class="btn btn-blue btn-inverted" target="_blank"><?php echo $_smarty_tpl->tpl_vars['page']->value['buttontext'];?>
<span class="icon icon-arrow-right"></span></a>
			<?php } else { ?>
				<a href="/product/<?php echo $_smarty_tpl->tpl_vars['page']->value['url'];?>
/bestellen" class="btn btn-blue btn-inverted"><?php echo $_smarty_tpl->tpl_vars['page']->value['buttontext'];?>
<span class="icon icon-arrow-right"></span></a>
			<?php }?>
	<?php }?>
</div>
<?php }?><?php }} ?>
