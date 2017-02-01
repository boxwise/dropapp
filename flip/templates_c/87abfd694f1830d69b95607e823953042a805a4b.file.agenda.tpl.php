<?php /* Smarty version Smarty-3.1.18, created on 2015-10-12 12:04:27
         compiled from "./templates/agenda.tpl" */ ?>
<?php /*%%SmartyHeaderCode:682303884556dac47a30699-03699222%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '87abfd694f1830d69b95607e823953042a805a4b' => 
    array (
      0 => './templates/agenda.tpl',
      1 => 1444644266,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '682303884556dac47a30699-03699222',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_556dac47a9ed33_34074445',
  'variables' => 
  array (
    'agenda' => 0,
    'month' => 0,
    'item' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556dac47a9ed33_34074445')) {function content_556dac47a9ed33_34074445($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/var/www/vhosts/global.zinnebeeld.nl/httpdocs/50-back/smarty/libs/plugins/modifier.date_format.php';
?><section class="agenda-overview">
	<div class="agenda-overview-items">
	<div class="container">
	<?php  $_smarty_tpl->tpl_vars['month'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['month']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['agenda']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['month']->key => $_smarty_tpl->tpl_vars['month']->value) {
$_smarty_tpl->tpl_vars['month']->_loop = true;
?>
		<div class="agenda-overview-header"><h4><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['month']->value['formatdate'],"%B");?>
 <?php echo $_smarty_tpl->tpl_vars['month']->value['year'];?>
</h4></div>
		<div class="row multi-columns-row">
			<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['month']->value['items']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
				<div class="col col-sm-6 col-md-4 col-lg-4 agenda-overview-item transitionFx fx-fadein">
					<a href="/product/<?php echo $_smarty_tpl->tpl_vars['item']->value['url'];?>
">
						<div class="agenda-overview-item-date">
							<div class="agenda-overview-item-date-day"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['agendadate'],"%e");?>
</div>
							<div class="agenda-overview-item-date-month"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['agendadate'],"%B");?>
</div>
						</div>
						<div class="agenda-overview-item-details">
							<div class="agenda-overview-item-details-subtitle"><?php echo $_smarty_tpl->tpl_vars['item']->value['agendasubtitle'];?>
</div>
							<div class="agenda-overview-item-details-title hyphenate"><?php echo $_smarty_tpl->tpl_vars['item']->value['pagetitle'];?>
</div>
							<div class="agenda-overview-item-details-info"><?php echo $_smarty_tpl->tpl_vars['item']->value['dateinfo'];?>
</div>
							<div class="agenda-overview-item-details-duration"><?php echo $_smarty_tpl->tpl_vars['item']->value['duration'];?>
</div>
						</div>
					</a>
				</div>
			<?php } ?>
		</div>
	<?php } ?>
	</div>
	</div>
</section><?php }} ?>
