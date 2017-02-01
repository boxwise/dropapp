<?php /* Smarty version Smarty-3.1.18, created on 2015-08-12 11:47:38
         compiled from "./templates/newsletter-subscribe.tpl" */ ?>
<?php /*%%SmartyHeaderCode:158264262655cb163ad8d732-17089692%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6c9db721809553d22011b2d517428c1b22d70135' => 
    array (
      0 => './templates/newsletter-subscribe.tpl',
      1 => 1439372852,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '158264262655cb163ad8d732-17089692',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'settings' => 0,
    'page' => 0,
    'translate' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55cb163ada9834_39301989',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55cb163ada9834_39301989')) {function content_55cb163ada9834_39301989($_smarty_tpl) {?>						<div class="newsletter-subscribe">
							<form id="newslettersubscribe" method="post" class="form" action="<?php echo $_smarty_tpl->tpl_vars['settings']->value['mailchimp_action'];?>
" novalidate="novalidate">
								<div class="form-row clearfix">
									<div class="mailchimp-hidden">
										<input type="text" name="<?php echo $_smarty_tpl->tpl_vars['settings']->value['mailchimp_hidden_code'];?>
" tabindex="-1" value="">
										<input type="hidden" value="Website inschrijfform" name="SOURCE" id="mce-SOURCE">
									</div>
									<ul class="clearfix">
										<li class="form-checkbox"><input type="checkbox" value="2" name="group[13721][2]" id="mce-group[13721]-13721-1"><label for="mce-group[13721]-13721-1"<?php if ($_smarty_tpl->tpl_vars['page']->value['subtemplate']=='newsletter') {?> class="label-lg"<?php }?>>Wij zijn een culturele organisatie</label></li>
										<li class="form-checkbox"><input type="checkbox" value="1" name="group[13721][1]" id="mce-group[13721]-13721-0"><label for="mce-group[13721]-13721-0"<?php if ($_smarty_tpl->tpl_vars['page']->value['subtemplate']=='newsletter') {?> class="label-lg"<?php }?>>Ik ben een kunstenaar/creatief</label></li>
									</ul>
									<div class="email-container">
										<input class="newsletter-input email required form-adjust" name="EMAIL" id="EMAIL" placeholder="<?php echo $_smarty_tpl->tpl_vars['translate']->value['newsletter_placeholder'];?>
" type="text" value=""><button type="submit" class="btn-submit-arrow"><span class="icon icon-arrow-right"></span></button>
									</div>
								</div>
							</form>
						</div><?php }} ?>
