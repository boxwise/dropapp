<?php /* Smarty version Smarty-3.1.18, created on 2016-02-19 12:37:14
         compiled from "/var/www/vhosts/global.zinnebeeld.nl/httpdocs/50-back/templates/cms_email.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1317423050556dd040b5e847-97341344%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2d36a597a79cfa63527daf80e4fb400dbcc0116c' => 
    array (
      0 => '/var/www/vhosts/global.zinnebeeld.nl/httpdocs/50-back/templates/cms_email.tpl',
      1 => 1439565355,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1317423050556dd040b5e847-97341344',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_556dd040bc6980_86049651',
  'variables' => 
  array (
    'domain' => 0,
    'translate' => 0,
    'content' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556dd040bc6980_86049651')) {function content_556dd040bc6980_86049651($_smarty_tpl) {?><html><head></head>
<body>
<table style="width: 540px;" id="body_holder" border="0" cellpadding="0" cellspacing="0">
<tbody>
<tr>
<td colspan="3" height="20"></td>
</tr>
<tr>
<td width="20"></td>
<td width="500"><a href="http://<?php echo $_smarty_tpl->tpl_vars['domain']->value;?>
" style="font-size: 3.3em; font-weight: normal; line-height: 120%; text-transform: uppercase;"><img src="http://<?php echo $_smarty_tpl->tpl_vars['domain']->value;?>
/global/img/flip-logo.png" /></a></td>
<td width="20"></td>
</tr>
<tr>
<td colspan="3" height="60"></td>
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
