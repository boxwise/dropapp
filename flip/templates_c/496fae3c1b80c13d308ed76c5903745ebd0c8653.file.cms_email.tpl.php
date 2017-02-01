<?php /* Smarty version Smarty-3.1.18, created on 2017-01-27 18:32:25
         compiled from "/home/drapeton/market/50-back/templates/cms_email.tpl" */ ?>
<?php /*%%SmartyHeaderCode:192980385588b76198d0240-44551620%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '496fae3c1b80c13d308ed76c5903745ebd0c8653' => 
    array (
      0 => '/home/drapeton/market/50-back/templates/cms_email.tpl',
      1 => 1484776980,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '192980385588b76198d0240-44551620',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'translate' => 0,
    'content' => 0,
    'domain' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_588b7619926304_81936514',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_588b7619926304_81936514')) {function content_588b7619926304_81936514($_smarty_tpl) {?><html><head></head>
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
