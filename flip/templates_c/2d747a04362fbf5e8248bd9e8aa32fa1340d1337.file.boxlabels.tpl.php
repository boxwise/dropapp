<?php /* Smarty version Smarty-3.1.18, created on 2017-02-09 12:44:05
         compiled from "./templates/boxlabels.tpl" */ ?>
<?php /*%%SmartyHeaderCode:408575958589b89c6808758-95333735%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2d747a04362fbf5e8248bd9e8aa32fa1340d1337' => 
    array (
      0 => './templates/boxlabels.tpl',
      1 => 1486637043,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '408575958589b89c6808758-95333735',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_589b89c68af475_31722368',
  'variables' => 
  array (
    'data' => 0,
    'd' => 0,
    'settings' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_589b89c68af475_31722368')) {function content_589b89c68af475_31722368($_smarty_tpl) {?><div class="noprint tipofday"><h3>💡 Best printing result</h3><p>Print it out using Google Chrome, choose A4, Portrait and switch off the printing of headers and footers in the Print dialog screen.</p></div>
<style type="text/css" media="print">
   <!--
   @page { margin: 0; }
   -->
</style>

<?php  $_smarty_tpl->tpl_vars['d'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['d']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['data']->value['labels']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['d']->key => $_smarty_tpl->tpl_vars['d']->value) {
$_smarty_tpl->tpl_vars['d']->_loop = true;
?>
<?php if ($_smarty_tpl->tpl_vars['data']->value['fulllabel']) {?>
<div class="boxlabel">
	<div class="boxlabel-qr"><img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=https://market.drapenihavet.no/flip/scan.php?barcode=<?php echo $_smarty_tpl->tpl_vars['d']->value['hash'];?>
" /></div>
	<div class="boxlabel-title">Drop In The Ocean</div>
	
	<div class="boxlabel-field boxlabel-field-short">Box Number<span class="boxlabel-data">&nbsp;<?php echo $_smarty_tpl->tpl_vars['d']->value['box_id'];?>
</span></div>
	<div class="boxlabel-contents"> <?php echo $_smarty_tpl->tpl_vars['d']->value['product'];?>
</div>
	<div class="boxlabel-field boxlabel-field-contents">Contents</div>
	<div class="boxlabel-field boxlabel-field-left">Gender<span class="boxlabel-data">&nbsp;<?php echo $_smarty_tpl->tpl_vars['d']->value['gender'];?>
</span></div>
	<div class="boxlabel-field boxlabel-field-right">Size<span class="boxlabel-data">&nbsp;<?php echo $_smarty_tpl->tpl_vars['d']->value['size'];?>
</span></div>
	
</div>
<?php } else { ?>
<div class="boxlabel-small">
	<img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=http://<?php echo $_SERVER['HTTP_HOST'];?>
<?php echo $_smarty_tpl->tpl_vars['settings']->value['rootdir'];?>
/flip/scan.php?barcode=<?php echo $_smarty_tpl->tpl_vars['d']->value['hash'];?>
" />
</div>
<?php }?>
<?php } ?>
<?php }} ?>
