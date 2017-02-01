<?php /* Smarty version Smarty-3.1.18, created on 2015-08-14 17:18:38
         compiled from "/var/www/vhosts/global.zinnebeeld.nl/httpdocs/50-back/templates/cms_form.tpl" */ ?>
<?php /*%%SmartyHeaderCode:932064638556dacf87f4008-00039187%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '04dc59ec7071641a0817f030e571dd19e4c568c9' => 
    array (
      0 => '/var/www/vhosts/global.zinnebeeld.nl/httpdocs/50-back/templates/cms_form.tpl',
      1 => 1439565356,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '932064638556dacf87f4008-00039187',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_556dacf8930422_96526678',
  'variables' => 
  array (
    'data' => 0,
    'translate' => 0,
    'title' => 0,
    'formelements' => 0,
    'element' => 0,
    'tabs' => 0,
    'key' => 0,
    'value' => 0,
    'formbuttons' => 0,
    'button' => 0,
    'settings' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556dacf8930422_96526678')) {function content_556dacf8930422_96526678($_smarty_tpl) {?><div class="content-form">
	<div class="row row-title">
		<div class="col-sm-12">
			<h1 id="form-title"><?php if (!$_smarty_tpl->tpl_vars['data']->value['id']) {?><?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_form_new'];?>
<?php } elseif ($_smarty_tpl->tpl_vars['title']->value) {?><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_form_edit'];?>
<?php }?></h1> 
		</div>
	</div>

	<form class="form form-horizontal areyousure" method="post">

		<input type="hidden" name="id" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['id'];?>
" />
		<input type="hidden" name="seq" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['seq'];?>
" />
		<input type="hidden" name="_origin" value="<?php echo $_GET['origin'];?>
" />
		<?php  $_smarty_tpl->tpl_vars['element'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['element']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['formelements']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['element']->key => $_smarty_tpl->tpl_vars['element']->value) {
$_smarty_tpl->tpl_vars['element']->_loop = true;
?>
			<?php if (!$_smarty_tpl->tpl_vars['element']->value['tab']&&!$_smarty_tpl->tpl_vars['element']->value['aside']) {?><?php echo $_smarty_tpl->getSubTemplate ("cms_form_".((string)$_smarty_tpl->tpl_vars['element']->value['type']).".tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }?>
		<?php } ?>	

		<?php if ($_smarty_tpl->tpl_vars['tabs']->value) {?>
			<?php if (count($_smarty_tpl->tpl_vars['tabs']->value)>1) {?>
				<ul class="nav nav-tabs">
					<?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['tabs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['value']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value) {
$_smarty_tpl->tpl_vars['value']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['value']->key;
 $_smarty_tpl->tpl_vars['value']->index++;
 $_smarty_tpl->tpl_vars['value']->first = $_smarty_tpl->tpl_vars['value']->index === 0;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']["tabs"]['first'] = $_smarty_tpl->tpl_vars['value']->first;
?>
					<li <?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['tabs']['first']) {?>class="active"<?php }?>><a href="#tab_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" data-toggle="tab"><?php echo $_smarty_tpl->tpl_vars['value']->value;?>
</a></li>
					<?php } ?>
				</ul>
			<?php }?>
			
			<div class="tab-content">
				<?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['tabs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['value']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value) {
$_smarty_tpl->tpl_vars['value']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['value']->key;
 $_smarty_tpl->tpl_vars['value']->index++;
 $_smarty_tpl->tpl_vars['value']->first = $_smarty_tpl->tpl_vars['value']->index === 0;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']["tabcontent"]['first'] = $_smarty_tpl->tpl_vars['value']->first;
?>
				<div class="tab-pane fade <?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['tabcontent']['first']) {?>in active<?php }?>" id="tab_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
">
				
					<?php  $_smarty_tpl->tpl_vars['element'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['element']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['formelements']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['element']->key => $_smarty_tpl->tpl_vars['element']->value) {
$_smarty_tpl->tpl_vars['element']->_loop = true;
?>
						<?php if ($_smarty_tpl->tpl_vars['element']->value['tab']&&$_smarty_tpl->tpl_vars['element']->value['tab']==$_smarty_tpl->tpl_vars['key']->value&&!$_smarty_tpl->tpl_vars['element']->value['aside']) {?><?php echo $_smarty_tpl->getSubTemplate ("cms_form_".((string)$_smarty_tpl->tpl_vars['element']->value['type']).".tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }?>
					<?php } ?>	
					
				</div>
				<?php } ?>
			</div>
		<?php }?>

		<aside id="aside-container">
			<div class="affix aside-content">
				<button name="__action" value="" class="btn btn-submit btn-success"><?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_form_submit'];?>
</button>
				<?php  $_smarty_tpl->tpl_vars['button'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['button']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['formbuttons']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['button']->key => $_smarty_tpl->tpl_vars['button']->value) {
$_smarty_tpl->tpl_vars['button']->_loop = true;
?>
					<button name="__action" value="<?php echo $_smarty_tpl->tpl_vars['button']->value['action'];?>
" class="btn btn-submit btn-success"><?php echo $_smarty_tpl->tpl_vars['button']->value['label'];?>
</button>
				<?php } ?>

				<a href="<?php echo $_smarty_tpl->tpl_vars['settings']->value['cmsdir'];?>
/?action=<?php echo $_GET['origin'];?>
" class="btn btn-cancel btn-default"><?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_form_cancel'];?>
</a>
				
				<div class="aside-form">
					<?php  $_smarty_tpl->tpl_vars['element'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['element']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['formelements']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['element']->key => $_smarty_tpl->tpl_vars['element']->value) {
$_smarty_tpl->tpl_vars['element']->_loop = true;
?>		
						<?php if ($_smarty_tpl->tpl_vars['element']->value['aside']) {?><?php echo $_smarty_tpl->getSubTemplate ("cms_form_".((string)$_smarty_tpl->tpl_vars['element']->value['type']).".tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }?>
					<?php } ?>	
				</div>
			</div>
		</aside>		
	</form>	
</div>
<?php }} ?>
