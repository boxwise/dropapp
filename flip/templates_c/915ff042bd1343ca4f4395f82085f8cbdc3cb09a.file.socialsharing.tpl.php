<?php /* Smarty version Smarty-3.1.18, created on 2015-08-18 14:08:38
         compiled from "./templates/socialsharing.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1366438907556dab44421296-14356588%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '915ff042bd1343ca4f4395f82085f8cbdc3cb09a' => 
    array (
      0 => './templates/socialsharing.tpl',
      1 => 1439899693,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1366438907556dab44421296-14356588',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_556dab444daee8_23368176',
  'variables' => 
  array (
    'page' => 0,
    'translate' => 0,
    'settings' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556dab444daee8_23368176')) {function content_556dab444daee8_23368176($_smarty_tpl) {?>						<?php if ($_smarty_tpl->tpl_vars['page']->value['sharing']) {?>
							<div class="socials article-socials">
								<h4><?php if (!$_smarty_tpl->tpl_vars['page']->value['producttype']||$_smarty_tpl->tpl_vars['page']->value['producttype']=='generic') {?><?php echo $_smarty_tpl->tpl_vars['translate']->value['share_desc'];?>
<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['translate']->value['product_share'];?>
<?php }?></h4>
								<div class="icons">
									<a class="icon-btn share-facebook" data-url="<?php echo $_smarty_tpl->tpl_vars['page']->value['canonical'];?>
"><span class="icon icon-facebook"></span></a>
									<a class="icon-btn share-twitter" data-url="<?php echo $_smarty_tpl->tpl_vars['page']->value['canonical'];?>
" data-title="<?php if ($_smarty_tpl->tpl_vars['page']->value['windowtitle']) {?><?php echo $_smarty_tpl->tpl_vars['page']->value['windowtitle'];?>
<?php }?><?php if ($_smarty_tpl->tpl_vars['page']->value['hide_site_name']!=true) {?> - <?php echo $_smarty_tpl->tpl_vars['settings']->value['site_name'];?>
<?php }?>" data-via="<?php echo $_smarty_tpl->tpl_vars['settings']->value['twitter_name'];?>
"><span class="icon icon-twitter"></span></a>
									<a class="icon-btn share-link" data-url="<?php echo $_smarty_tpl->tpl_vars['page']->value['canonical'];?>
" data-title="<?php if ($_smarty_tpl->tpl_vars['page']->value['windowtitle']) {?><?php echo $_smarty_tpl->tpl_vars['page']->value['windowtitle'];?>
<?php }?><?php if ($_smarty_tpl->tpl_vars['page']->value['hide_site_name']!=true) {?> - <?php echo $_smarty_tpl->tpl_vars['settings']->value['site_name'];?>
<?php }?>"><span class="icon icon-link"></span></a>
									<a class="icon-btn share-email" data-url="<?php echo $_smarty_tpl->tpl_vars['page']->value['canonical'];?>
" data-title="<?php if ($_smarty_tpl->tpl_vars['page']->value['windowtitle']) {?><?php echo $_smarty_tpl->tpl_vars['page']->value['windowtitle'];?>
<?php }?><?php if ($_smarty_tpl->tpl_vars['page']->value['hide_site_name']!=true) {?> - <?php echo $_smarty_tpl->tpl_vars['settings']->value['site_name'];?>
<?php }?>" data-leestip="<?php echo $_smarty_tpl->tpl_vars['translate']->value['emailshare_leestip'];?>
" data-message="<?php echo $_smarty_tpl->tpl_vars['translate']->value['emailshare_message'];?>
"><span class="icon icon-email"></span></a>
									<a class="icon-btn share-linkedin" data-url="<?php echo $_smarty_tpl->tpl_vars['page']->value['canonical'];?>
" data-title="<?php if ($_smarty_tpl->tpl_vars['page']->value['windowtitle']) {?><?php echo $_smarty_tpl->tpl_vars['page']->value['windowtitle'];?>
<?php }?><?php if ($_smarty_tpl->tpl_vars['page']->value['hide_site_name']!=true) {?> - <?php echo $_smarty_tpl->tpl_vars['settings']->value['site_name'];?>
<?php }?>"><span class="icon icon-linkedin"></span></a>
									<a class="icon-btn share-googleplus" data-url="<?php echo $_smarty_tpl->tpl_vars['page']->value['canonical'];?>
" data-title="<?php if ($_smarty_tpl->tpl_vars['page']->value['windowtitle']) {?><?php echo $_smarty_tpl->tpl_vars['page']->value['windowtitle'];?>
<?php }?><?php if ($_smarty_tpl->tpl_vars['page']->value['hide_site_name']!=true) {?> - <?php echo $_smarty_tpl->tpl_vars['settings']->value['site_name'];?>
<?php }?>"><span class="icon icon-googleplus"></span></a>
									<a class="icon-btn share-whatsapp" data-url="<?php echo $_smarty_tpl->tpl_vars['page']->value['canonical'];?>
" data-title="<?php if ($_smarty_tpl->tpl_vars['page']->value['windowtitle']) {?><?php echo $_smarty_tpl->tpl_vars['page']->value['windowtitle'];?>
<?php }?><?php if ($_smarty_tpl->tpl_vars['page']->value['hide_site_name']!=true) {?> - <?php echo $_smarty_tpl->tpl_vars['settings']->value['site_name'];?>
<?php }?>"><span class="icon icon-whatsapp"></span></a>
								</div>
							</div>
						<?php }?>



<?php }} ?>
