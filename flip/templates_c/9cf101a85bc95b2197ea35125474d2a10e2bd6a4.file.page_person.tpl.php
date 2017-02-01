<?php /* Smarty version Smarty-3.1.18, created on 2015-06-02 15:10:28
         compiled from "./templates/page_person.tpl" */ ?>
<?php /*%%SmartyHeaderCode:575842683556dab444e2465-69019632%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9cf101a85bc95b2197ea35125474d2a10e2bd6a4' => 
    array (
      0 => './templates/page_person.tpl',
      1 => 1433248474,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '575842683556dab444e2465-69019632',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'owner' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_556dab4452ac46_62179664',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556dab4452ac46_62179664')) {function content_556dab4452ac46_62179664($_smarty_tpl) {?>								<div class="article-contact clearfix">
									<div class="article-contact-picture" style="background:url('<?php if ($_smarty_tpl->tpl_vars['owner']->value['image']) {?>/content/mensen_thumbs/<?php echo $_smarty_tpl->tpl_vars['owner']->value['image'];?>
<?php } else { ?>/site/img/profile-default.svg<?php }?>'); background-size: cover;"><a href="/medewerkers/<?php echo $_smarty_tpl->tpl_vars['owner']->value['url'];?>
"></a></div>
									<div class="article-contact-details">
										<div class="article-contact-details-name"><a href="/medewerkers/<?php echo $_smarty_tpl->tpl_vars['owner']->value['url'];?>
"><?php echo $_smarty_tpl->tpl_vars['owner']->value['fullname'];?>
</a></div>
										<div class="article-contact-details-function"><?php echo $_smarty_tpl->tpl_vars['owner']->value['function'];?>
</div>
										<div class="article-contact-details-phone"><?php echo $_smarty_tpl->tpl_vars['owner']->value['tel'];?>
</div>
										<div class="article-contact-details-mail"><a href="mailto:<?php echo $_smarty_tpl->tpl_vars['owner']->value['email'];?>
"><?php echo $_smarty_tpl->tpl_vars['owner']->value['email'];?>
</a></div>
										<div class="article-contact-details-socials">
											<?php if ($_smarty_tpl->tpl_vars['owner']->value['twitter']) {?><a href="<?php echo $_smarty_tpl->tpl_vars['owner']->value['twitter'];?>
" target="_blank" class="icon-btn"><span class="icon icon-twitter"></span></a><?php }?>
											<?php if ($_smarty_tpl->tpl_vars['owner']->value['linkedin']) {?><a href="<?php echo $_smarty_tpl->tpl_vars['owner']->value['linkedin'];?>
" target="_blank" class="icon-btn"><span class="icon icon-linkedin"></span></a><?php }?>
											<?php if ($_smarty_tpl->tpl_vars['owner']->value['website']) {?><a href="<?php echo $_smarty_tpl->tpl_vars['owner']->value['website'];?>
" target="_blank" class="icon-btn"><span class="icon icon-globe"></span></a><?php }?>
										</div>
									</div>
								</div><?php }} ?>
