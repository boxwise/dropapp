<?php /* Smarty version Smarty-3.1.18, created on 2016-11-07 17:04:01
         compiled from "/home/deb5672n12/domains/maartenhunink.com/public_html/drops/50-back/templates/cms_email.tpl" */ ?>
<?php /*%%SmartyHeaderCode:16596746675810c47f2692a5-29690714%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'df37417c7a15822f30093e9d9ae4513e419c84d5' => 
    array (
      0 => '/home/deb5672n12/domains/maartenhunink.com/public_html/drops/50-back/templates/cms_email.tpl',
      1 => 1478348988,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16596746675810c47f2692a5-29690714',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_5810c47f27ff39_40013246',
  'variables' => 
  array (
    'translate' => 0,
    'content' => 0,
    'domain' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5810c47f27ff39_40013246')) {function content_5810c47f27ff39_40013246($_smarty_tpl) {?><html><head></head>
<body>
<table style="width: 540px;" id="body_holder" border="0" cellpadding="0" cellspacing="0">
<tbody>
<tr>
<td colspan="3" height="20"></td>
</tr>
<tr>
<td width="20"></td>
<td>
<p>&nbsp;<br /><span style="font-family: arial, sans-serif; font-size: 14px; line-height: 135%;"><?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_email_salutation'];?>
 *|NAAM|*,</span><br />&nbsp;</p>
<p><span style="font-family: arial, sans-serif; font-size: 14px; line-height: 135%;"><?php echo $_smarty_tpl->tpl_vars['content']->value;?>
</span><br />&nbsp;</p>
<p><span style="font-family: arial, sans-serif; font-size: 14px; line-height: 135%;"><?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_email_signature'];?>
</span><br />&nbsp;</p>
</td>
<td width="20"></td>
</tr>
<tr>
<td colspan="3" height="20"></td>
</tr>
<tr>
<td width="20"></td>
<td>
<p><span style="font-family: arial, sans-serif; font-size: 11px; line-height: 135%;"><?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_email_boilerplate'];?>
 <a href="http://<?php echo $_smarty_tpl->tpl_vars['domain']->value;?>
" target="_blank" style="color: #29ABE2; text-decoration: underline;"><?php echo $_smarty_tpl->tpl_vars['domain']->value;?>
</a></span></p>
</td>
<td width="20"></td>
</tr>
<tr>
<td colspan="3" height="20"></td>
</tr>
</tbody>
</table>
</body>
</html><?php }} ?>
