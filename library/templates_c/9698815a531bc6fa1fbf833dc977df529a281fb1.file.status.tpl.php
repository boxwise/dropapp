<?php /* Smarty version Smarty-3.1.18, created on 2017-02-15 19:20:06
         compiled from "/Users/bart/Websites/themarket/library/templates/status.tpl" */ ?>
<?php /*%%SmartyHeaderCode:162806980958a48dc63a2968-83413055%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9698815a531bc6fa1fbf833dc977df529a281fb1' => 
    array (
      0 => '/Users/bart/Websites/themarket/library/templates/status.tpl',
      1 => 1486395498,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '162806980958a48dc63a2968-83413055',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'data' => 0,
    'translate' => 0,
    'adults' => 0,
    'children' => 0,
    'container' => 0,
    'dropcoins' => 0,
    'formelements' => 0,
    'element' => 0,
    'tabs' => 0,
    'key' => 0,
    'value' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_58a48dc64758e1_39442951',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58a48dc64758e1_39442951')) {function content_58a48dc64758e1_39442951($_smarty_tpl) {?><div class="content-form">
	<div class="row row-title">
		<div class="col-sm-12">
			<h1 id="form-title"><?php if ($_smarty_tpl->tpl_vars['title']->value) {?><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
<?php } else { ?><?php if (!$_smarty_tpl->tpl_vars['data']->value['id']) {?><?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_form_new'];?>
<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_form_edit'];?>
<?php }?><?php }?>
			</h1>
			<p class="title-details"><?php if ($_smarty_tpl->tpl_vars['adults']->value) {?><?php echo $_smarty_tpl->tpl_vars['translate']->value['adults'];?>
: <?php echo $_smarty_tpl->tpl_vars['adults']->value;?>
<?php }?><?php if ($_smarty_tpl->tpl_vars['children']->value) {?>, <?php echo $_smarty_tpl->tpl_vars['translate']->value['children'];?>
: <?php echo $_smarty_tpl->tpl_vars['children']->value;?>
<?php }?><br />
			<?php if ($_smarty_tpl->tpl_vars['container']->value) {?><?php echo $_smarty_tpl->tpl_vars['translate']->value['container'];?>
: <?php echo $_smarty_tpl->tpl_vars['container']->value;?>
<?php }?></p>
			<?php if ($_smarty_tpl->tpl_vars['dropcoins']->value) {?><p class="dropcoins"><i class="fa fa-tint"></i> <span class="dropcoins-text"><b><?php echo $_smarty_tpl->tpl_vars['dropcoins']->value;?>
</b><br /><?php echo $_smarty_tpl->tpl_vars['translate']->value['coins'];?>
</span></p><?php }?>
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
	</form>	
</div>
<?php }} ?>
