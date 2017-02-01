<?php /* Smarty version Smarty-3.1.18, created on 2015-08-18 11:54:51
         compiled from "/var/www/vhosts/global.zinnebeeld.nl/httpdocs/50-back/templates/cms_linkmanager_index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1072305093556dd1c3da4077-84258976%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5e2c16af291bf546f0e6b16cd37f7140391b8e76' => 
    array (
      0 => '/var/www/vhosts/global.zinnebeeld.nl/httpdocs/50-back/templates/cms_linkmanager_index.tpl',
      1 => 1439565357,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1072305093556dd1c3da4077-84258976',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_556dd1c3e419c5_62999967',
  'variables' => 
  array (
    'content' => 0,
    'linksList' => 0,
    'links' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556dd1c3e419c5_62999967')) {function content_556dd1c3e419c5_62999967($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("cms_linkmanager_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>


<!-- Your Content -->
<div id="container">

	<div class="container-fluid">
	
		<?php echo $_smarty_tpl->tpl_vars['content']->value;?>
						
		
		<div class="links-frame-container">
			<?php  $_smarty_tpl->tpl_vars['links'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['links']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['linksList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['links']->key => $_smarty_tpl->tpl_vars['links']->value) {
$_smarty_tpl->tpl_vars['links']->_loop = true;
?>
				<?php echo $_smarty_tpl->getSubTemplate ("cms_linkmanager_linkslist.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('links'=>$_smarty_tpl->tpl_vars['links']->value['links'],'title'=>$_smarty_tpl->tpl_vars['links']->value['title'],'prefix'=>$_smarty_tpl->tpl_vars['links']->value['prefix']), 0);?>
		
			<?php } ?>
		</div>
		
	</div>
</div>

<?php echo $_smarty_tpl->getSubTemplate ("cms_linkmanager_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
