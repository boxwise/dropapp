<?php /* Smarty version Smarty-3.1.18, created on 2015-06-02 15:13:47
         compiled from "./templates/people_overview_person.tpl" */ ?>
<?php /*%%SmartyHeaderCode:415719993556dac0bcaf296-53484725%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'cea5117b2fab4accd2ec88c990febb658d417f69' => 
    array (
      0 => './templates/people_overview_person.tpl',
      1 => 1433248474,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '415719993556dac0bcaf296-53484725',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'category' => 0,
    'person' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_556dac0bcd3f64_07789322',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556dac0bcd3f64_07789322')) {function content_556dac0bcd3f64_07789322($_smarty_tpl) {?>				<div class="col col-sm-6 col-md-4 col-lg-3  transitionFx fx-fadein people-overview-person<?php if ($_smarty_tpl->tpl_vars['category']->value['trainers']||$_smarty_tpl->tpl_vars['category']->value['mentors']) {?> person-smaller<?php }?>">
					<a href="/medewerkers/<?php echo $_smarty_tpl->tpl_vars['person']->value['url'];?>
">
						<div class="people-overview-person-picture" style="background:url('<?php if ($_smarty_tpl->tpl_vars['person']->value['image']) {?>/content/mensen_thumbs/<?php echo $_smarty_tpl->tpl_vars['person']->value['image'];?>
<?php } else { ?>/site/img/profile-default.svg<?php }?>'); background-size: cover;">
						</div>
						<div class="people-overview-person-details">
							<div class="people-overview-person-details-name"><?php echo $_smarty_tpl->tpl_vars['person']->value['fullname'];?>
</div>
							<div class="people-overview-person-details-function"><?php echo $_smarty_tpl->tpl_vars['person']->value['function'];?>
</div>
						</div>
					</a>
				</div><?php }} ?>
