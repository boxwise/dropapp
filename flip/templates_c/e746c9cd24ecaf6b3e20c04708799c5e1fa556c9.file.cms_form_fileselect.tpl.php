<?php /* Smarty version Smarty-3.1.18, created on 2015-08-18 12:07:53
         compiled from "/var/www/vhosts/global.zinnebeeld.nl/httpdocs/50-back/templates/cms_form_fileselect.tpl" */ ?>
<?php /*%%SmartyHeaderCode:963574069556db40d0dcd21-50965293%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e746c9cd24ecaf6b3e20c04708799c5e1fa556c9' => 
    array (
      0 => '/var/www/vhosts/global.zinnebeeld.nl/httpdocs/50-back/templates/cms_form_fileselect.tpl',
      1 => 1439565355,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '963574069556db40d0dcd21-50965293',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_556db40d2449e6_37798344',
  'variables' => 
  array (
    'element' => 0,
    'flipdir' => 0,
    'translate' => 0,
    'data' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556db40d2449e6_37798344')) {function content_556db40d2449e6_37798344($_smarty_tpl) {?>	<div class="form-group<?php if ($_smarty_tpl->tpl_vars['element']->value['hidden']) {?> hidden<?php }?>" id="div_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
">
		<input type="hidden" name="__<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" value="fileselect <?php echo $_smarty_tpl->tpl_vars['element']->value['filetype'];?>
 <?php if ($_smarty_tpl->tpl_vars['element']->value['readonly']) {?>readonly<?php }?>">
		<input type="hidden" name="__<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
resize" value="<?php echo $_smarty_tpl->tpl_vars['element']->value['resizeproperties'];?>
">
		<label for="field_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" class="control-label col-sm-2"><?php echo $_smarty_tpl->tpl_vars['element']->value['label'];?>
</label>
		<div class="col-sm-10 input-element">

			<?php if ($_smarty_tpl->tpl_vars['element']->value['filetype']=='image') {?>
				<a href="<?php echo $_smarty_tpl->tpl_vars['flipdir']->value;?>
/filemanager/dialog.php?type=1<?php if ($_smarty_tpl->tpl_vars['element']->value['folder']) {?>&fldr=<?php echo $_smarty_tpl->tpl_vars['element']->value['folder'];?>
<?php }?>&field_id=field_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" class="btn btn-sm btn-default popupfilemanager" data-btn-change-msg="<?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_form_changeimage_msg'];?>
" data-btn-change-label="<i class='fa fa-image'></i> <?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_form_changeimage'];?>
" data-btn-choose-label="<i class='fa fa-image'></i> <?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_form_chooseimage'];?>
"><i class="fa fa-image"></i> <?php if ($_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['element']->value['field']]) {?><?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_form_changeimage'];?>
<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_form_chooseimage'];?>
<?php }?></a>
			<?php } else { ?>
				<a href="<?php echo $_smarty_tpl->tpl_vars['flipdir']->value;?>
/filemanager/dialog.php?type=2<?php if ($_smarty_tpl->tpl_vars['element']->value['folder']) {?>&fldr=<?php echo $_smarty_tpl->tpl_vars['element']->value['folder'];?>
<?php }?>&field_id=field_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" class="btn btn-sm btn-default popupfilemanager" data-btn-change-msg="<?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_form_changefile_msg'];?>
" data-btn-change-label="<i class='fa fa-file-o'></i> <?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_form_changefile'];?>
" data-btn-choose-label="<i class='fa fa-file-o'></i> <?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_form_choosefile'];?>
"><i class="fa fa-file-o"></i> <?php if ($_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['element']->value['field']]) {?><?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_form_changefile'];?>
<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_form_choosefile'];?>
<?php }?></a>			
			<?php }?>	
			<a href="#" class="btn btn-sm confirm btn-danger file-remove<?php if ($_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['element']->value['field']]) {?> active<?php }?>" data-btn-ok-label="<?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_form_delete'];?>
" data-btn-cancel-label="<?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_form_cancel'];?>
" data-btn-erase-label="<i class='fa fa-eraser'></i> <?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_form_delete'];?>
" data-btn-delete-label="<i class='fa fa-trash-o'></i> <?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_form_delete'];?>
" data-title="<?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_form_deletefileconfirmation'];?>
" data-placement="top" data-original-title="" title=""><i class="fa fa-trash-o"></i> <?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_form_delete'];?>
</a>
			<input type="hidden" class="file_name" name="<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" id="field_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" data-fieldid="<?php echo $_smarty_tpl->tpl_vars['element']->value['fieldid'];?>
" value="<?php echo $_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['element']->value['field']];?>
" />					
			<?php if ($_smarty_tpl->tpl_vars['element']->value['tooltip']) {?><?php echo $_smarty_tpl->getSubTemplate ("cms_tooltip.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('valign'=>" relative"), 0);?>
<?php }?>

		<?php if ($_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['element']->value['field']]) {?>
			<div class="file-preview active">			
				<input type="hidden" name="file_prev" class="file_prev" value="<?php echo $_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['element']->value['field']];?>
" />
			<?php if ($_smarty_tpl->tpl_vars['element']->value['filetype']=='image') {?>
				<img src="<?php echo $_smarty_tpl->tpl_vars['element']->value['preview'];?>
" width="200"<?php if ($_smarty_tpl->tpl_vars['element']->value['background']) {?> style="background-color: <?php echo $_smarty_tpl->tpl_vars['element']->value['background'];?>
"<?php }?> />
			<?php } else { ?>
				<a href="<?php echo $_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['element']->value['field']];?>
" target="_blank"><i class="fa glyphicon fa-file-<?php echo $_smarty_tpl->tpl_vars['element']->value['icon'];?>
"></i><?php echo $_smarty_tpl->tpl_vars['element']->value['basename'];?>
</a>
			<?php }?>
			</div>	
		<?php } else { ?>
			<div class="file-preview">
				<input type="hidden" name="file_prev" class="file_prev" value="" />			
			<?php if ($_smarty_tpl->tpl_vars['element']->value['filetype']=='image') {?>
				<img src="" width="200"<?php if ($_smarty_tpl->tpl_vars['element']->value['background']) {?> style="background-color: <?php echo $_smarty_tpl->tpl_vars['element']->value['background'];?>
"<?php }?> />
			<?php } else { ?>
				<a href="" target="_blank"><i class="fa glyphicon"></i> <span>filename here</span></a>
			<?php }?>			
			</div>
		<?php }?>
		</div>
	</div><?php }} ?>
