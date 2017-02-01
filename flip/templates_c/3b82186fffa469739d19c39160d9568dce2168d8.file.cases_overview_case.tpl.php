<?php /* Smarty version Smarty-3.1.18, created on 2016-09-21 15:26:16
         compiled from "./templates/cases_overview_case.tpl" */ ?>
<?php /*%%SmartyHeaderCode:136905585857e28a78b887e8-33998819%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3b82186fffa469739d19c39160d9568dce2168d8' => 
    array (
      0 => './templates/cases_overview_case.tpl',
      1 => 1474464016,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '136905585857e28a78b887e8-33998819',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'case' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_57e28a78bdc175_36788741',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57e28a78bdc175_36788741')) {function content_57e28a78bdc175_36788741($_smarty_tpl) {?>				<div class="col col-sm-6 col-md-4 col-lg-3  transitionFx fx-fadein cases-overview-case case-smaller">
					<a href="/ervaringsverhalen/<?php echo $_smarty_tpl->tpl_vars['case']->value['url'];?>
">
						<div class="cases-overview-case-picture" style="background:url('<?php if ($_smarty_tpl->tpl_vars['case']->value['image']) {?>/content/cases_thumbs/<?php echo $_smarty_tpl->tpl_vars['case']->value['image'];?>
<?php } else { ?>/site/img/profile-default.svg<?php }?>'); background-size: cover;">
						</div>
						<div class="cases-overview-case-details">
							<div class="cases-overview-case-details-name"><?php echo $_smarty_tpl->tpl_vars['case']->value['title'];?>
</div>
							<div class="cases-overview-case-details-function"><?php echo $_smarty_tpl->tpl_vars['case']->value['function'];?>
</div>
						</div>
					</a>
				</div><?php }} ?>
