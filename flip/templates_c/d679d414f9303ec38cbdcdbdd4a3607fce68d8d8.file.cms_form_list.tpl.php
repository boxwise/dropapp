<?php /* Smarty version Smarty-3.1.18, created on 2017-01-25 00:14:31
         compiled from "/home/drapeton/market/50-back/templates/cms_form_list.tpl" */ ?>
<?php /*%%SmartyHeaderCode:7797916265881d2cc1abde3-68450401%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd679d414f9303ec38cbdcdbdd4a3607fce68d8d8' => 
    array (
      0 => '/home/drapeton/market/50-back/templates/cms_form_list.tpl',
      1 => 1485296068,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7797916265881d2cc1abde3-68450401',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_5881d2cc269021_68404940',
  'variables' => 
  array (
    'element' => 0,
    'translate' => 0,
    'code' => 0,
    'button' => 0,
    'listconfig' => 0,
    'key' => 0,
    'option' => 0,
    'column' => 0,
    'row' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5881d2cc269021_68404940')) {function content_5881d2cc269021_68404940($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_truncate')) include '/home/drapeton/market/50-back/smarty/libs/plugins/modifier.truncate.php';
?>	<div class="form-group<?php if ($_smarty_tpl->tpl_vars['element']->value['hidden']) {?> hidden<?php }?>" id="div_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" <?php if ($_smarty_tpl->tpl_vars['element']->value['listid']) {?>data-listid="<?php echo $_smarty_tpl->tpl_vars['element']->value['listid'];?>
"<?php }?>>
		<input type="hidden" name="__<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" value="text <?php if ($_smarty_tpl->tpl_vars['element']->value['format']!='') {?><?php echo $_smarty_tpl->tpl_vars['element']->value['format'];?>
<?php }?> <?php if ($_smarty_tpl->tpl_vars['element']->value['readonly']) {?>readonly<?php }?>">
		
		<label for="field_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" class="control-label col-sm-2"><?php echo $_smarty_tpl->tpl_vars['element']->value['label'];?>
</label>
		
		<div class="col-sm-<?php if ($_smarty_tpl->tpl_vars['element']->value['width']>0&&$_smarty_tpl->tpl_vars['element']->value['width']<11) {?><?php echo $_smarty_tpl->tpl_vars['element']->value['width'];?>
<?php } else { ?>6<?php }?>">
			
		<div class="table-parent <?php if ($_smarty_tpl->tpl_vars['element']->value['allowsort']) {?>sortable<?php }?> <?php if ($_smarty_tpl->tpl_vars['element']->value['allowmove']) {?>zortable<?php }?>" data-table="tablename" data-startlevel="0" data-maxlevel="0" data-action="?action=<?php echo $_smarty_tpl->tpl_vars['element']->value['action'];?>
" data-saveonchange="1" <?php if ($_smarty_tpl->tpl_vars['element']->value['maxheight']) {?>data-maxheight="<?php echo $_smarty_tpl->tpl_vars['element']->value['maxheight'];?>
"<?php }?> <?php if ($_smarty_tpl->tpl_vars['element']->value['modal']) {?>data-modal="true"<?php }?> data-inside-form="true">

			<div class="table-nav">
				<ul class="actions">
					<?php if ($_smarty_tpl->tpl_vars['element']->value['allowselectall']) {?><li>
						<label class="btn btn-default btn-sm tooltip-this" data-toggle="tooltip" data-placement="top" title="<?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_list_selectall'];?>
" for="group-select-1"><input id="group-select-1" type="checkbox" class="group-select"></label>
					</li><?php }?>
					<li class="items-selected-dependent">
						<div class="btn-group">
						
							<?php if ($_smarty_tpl->tpl_vars['element']->value['allowshowhide']) {?><button data-operation="show" class="start-operation btn btn-default btn-sm" href="#"><i class="fa glyphicon fa-eye"></i> <?php echo $_smarty_tpl->tpl_vars['element']->value['show'];?>
</button>
							<button data-operation="hide" class="start-operation btn btn-default btn-sm" href="#"><i class="fa fa-eye-slash"></i> <?php echo $_smarty_tpl->tpl_vars['element']->value['hide'];?>
</button><?php }?>
							
							<?php if ($_smarty_tpl->tpl_vars['element']->value['allowdelete']) {?><button data-operation="delete" data-placement="top" data-title="<?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_list_confirm_title'];?>
" data-btn-ok-label="<?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_list_confirm_ok'];?>
" data-btn-cancel-label="<?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_list_confirm_cancel'];?>
" class="start-operation btn btn-sm confirm btn-danger" href="#"><i class="fa fa-trash-o"></i> <?php echo $_smarty_tpl->tpl_vars['element']->value['delete'];?>
</button><?php }?>
							
							<?php if ($_smarty_tpl->tpl_vars['element']->value['allowcopy']) {?><button data-operation="copy" data-placement="top" class="start-operation btn btn-sm btn-default" href="#"><i class="fa fa-copy"></i> <?php echo $_smarty_tpl->tpl_vars['element']->value['copy'];?>
</button><?php }?>
							
							<?php  $_smarty_tpl->tpl_vars['button'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['button']->_loop = false;
 $_smarty_tpl->tpl_vars['code'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['element']->value['button']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['button']->key => $_smarty_tpl->tpl_vars['button']->value) {
$_smarty_tpl->tpl_vars['button']->_loop = true;
 $_smarty_tpl->tpl_vars['code']->value = $_smarty_tpl->tpl_vars['button']->key;
?>
								<button data-operation="<?php echo $_smarty_tpl->tpl_vars['code']->value;?>
" data-placement="top" class="start-operation btn btn-sm <?php if ($_smarty_tpl->tpl_vars['button']->value['confirm']) {?>confirm<?php }?> btn-default <?php if ($_smarty_tpl->tpl_vars['button']->value['oneitemonly']) {?>one-item-only<?php }?>" href="#"><?php if ($_smarty_tpl->tpl_vars['button']->value['icon']) {?><i class="fa <?php echo $_smarty_tpl->tpl_vars['button']->value['icon'];?>
"></i> <?php }?><?php echo $_smarty_tpl->tpl_vars['button']->value['label'];?>
</button>
							<?php } ?>
						</div>
					</li>
					<?php if ($_smarty_tpl->tpl_vars['element']->value['allowadd']) {?>
					<li>
						<a class="new-page item-add btn btn-sm btn-default" href="?action=<?php if ($_smarty_tpl->tpl_vars['element']->value['addaction']) {?><?php echo $_smarty_tpl->tpl_vars['element']->value['addaction'];?>
<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['element']->value['action'];?>
_edit<?php }?><?php if ($_smarty_tpl->tpl_vars['element']->value['modal']) {?>&modal=1<?php }?>&origin=<?php echo $_smarty_tpl->tpl_vars['element']->value['origin'];?>
"><i class="fa fa-plus"></i> <?php echo $_smarty_tpl->tpl_vars['element']->value['add'];?>
</a>
					</li>
					<?php }?>
				</ul>
				<ul class="navigations pull-right list-unstyled">
					<?php if ($_smarty_tpl->tpl_vars['listconfig']->value['filter']) {?>
						<li>
							<div class="btn-group">
								<div type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
									<?php if ($_smarty_tpl->tpl_vars['listconfig']->value['filtervalue']) {?>
										<?php echo $_smarty_tpl->tpl_vars['listconfig']->value['filter']['options'][$_smarty_tpl->tpl_vars['listconfig']->value['filtervalue']];?>

									<?php } else { ?>
										<?php echo $_smarty_tpl->tpl_vars['listconfig']->value['filter']['label'];?>

									<?php }?>
									<?php if ($_smarty_tpl->tpl_vars['listconfig']->value['filtervalue']) {?>
										<a class="fa fa-times form-control-feedback" href="?action=<?php echo $_smarty_tpl->tpl_vars['listconfig']->value['origin'];?>
&resetfilter=true"></a>
									<?php } else { ?>
										<span class="caret"></span>
									<?php }?>
								</div>
								<ul class="dropdown-menu pull-right" role="menu">
									<?php  $_smarty_tpl->tpl_vars['option'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['option']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['listconfig']->value['filter']['options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['option']->key => $_smarty_tpl->tpl_vars['option']->value) {
$_smarty_tpl->tpl_vars['option']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['option']->key;
?>
										<li><a href="?action=<?php echo $_smarty_tpl->tpl_vars['listconfig']->value['origin'];?>
&filter=<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['option']->value;?>
</a></li></li> 
									<?php } ?>
								</ul>
							</div>
						</li>
					<?php }?>
					<?php if ($_smarty_tpl->tpl_vars['listconfig']->value['search']) {?>
						<li>
							<form method="post">
								<div class="input-group form-inline search-group">							
									<div class="has-feedback">
										<input type="text" class="form-control input-sm" name="search" value="<?php echo $_smarty_tpl->tpl_vars['listconfig']->value['searchvalue'];?>
">								
										<?php if ($_smarty_tpl->tpl_vars['listconfig']->value['searchvalue']) {?><a class="fa fa-times form-control-feedback" href="?action=<?php echo $_smarty_tpl->tpl_vars['listconfig']->value['origin'];?>
&resetsearch=true"></a><?php }?>
									</div>
									<span class="input-group-btn">
										<button class="btn btn-sm btn-default" type="button"><span class="fa fa-search"></span></button>
									</span>
								</div>
							</form>
						</li>
					<?php }?>
				</ul>
				<div class="fc"></div>
			</div>	


			<div class="sticky-header-container">
				<table class="table" <?php if ($_smarty_tpl->tpl_vars['element']->value['sortlist']) {?>data-sortlist="<?php echo $_smarty_tpl->tpl_vars['element']->value['sortlist'];?>
"<?php }?>>
				  	<thead>
					  	<tr>
						<?php  $_smarty_tpl->tpl_vars['column'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['column']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['element']->value['columns']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['column']->key => $_smarty_tpl->tpl_vars['column']->value) {
$_smarty_tpl->tpl_vars['column']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['column']->key;
?>
					  		<th <?php if ($_smarty_tpl->tpl_vars['column']->value['width']) {?> width="<?php echo $_smarty_tpl->tpl_vars['column']->value['width'];?>
<?php }?>" data-rowname="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['column']->value;?>
</th>
						<?php } ?>
					  	</tr>
					</thead>
					<tbody>
				      <?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['element']->value['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value) {
$_smarty_tpl->tpl_vars['row']->_loop = true;
?>
						<tr data-id="<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
" data-level="0" class="item <?php if (isset($_smarty_tpl->tpl_vars['row']->value['visible'])&&!$_smarty_tpl->tpl_vars['row']->value['visible']) {?>item-hidden<?php }?> level-0 
							<?php if ($_smarty_tpl->tpl_vars['element']->value['allowedit']||!isset($_smarty_tpl->tpl_vars['element']->value['allowedit'])) {?>item-clickable <?php }?>
							<?php if ($_smarty_tpl->tpl_vars['element']->value['allowmove']) {?>item-zortable<?php }?> 
							<?php if ($_smarty_tpl->tpl_vars['element']->value['allowselect']) {?>item-selectable<?php }?>">
						<?php  $_smarty_tpl->tpl_vars['column'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['column']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['element']->value['columns']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['column']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['column']->key => $_smarty_tpl->tpl_vars['column']->value) {
$_smarty_tpl->tpl_vars['column']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['column']->key;
 $_smarty_tpl->tpl_vars['column']->index++;
 $_smarty_tpl->tpl_vars['column']->first = $_smarty_tpl->tpl_vars['column']->index === 0;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']["rows"]['first'] = $_smarty_tpl->tpl_vars['column']->first;
?>
							<?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['rows']['first']) {?>
								<td class="controls-front list-level-0">
									<div class="td-content">
										<div class="handle"><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span></div>						
										<label class="item-select-label"><input class="item-select" type="checkbox"></label>
											<?php if ($_smarty_tpl->tpl_vars['element']->value['allowedit']||!isset($_smarty_tpl->tpl_vars['element']->value['allowedit'])) {?>
												<a class="td-content-field data-field-<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" href="?action=<?php echo $_smarty_tpl->tpl_vars['element']->value['action'];?>
_edit<?php if ($_smarty_tpl->tpl_vars['element']->value['modal']) {?>&modal=1<?php }?>&id=<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
">
											<?php } else { ?>
												<p class="td-content-field data-field-<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
">
											<?php }?>
											<?php echo smarty_modifier_truncate(strip_tags($_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['key']->value]));?>

											<?php if ($_smarty_tpl->tpl_vars['element']->value['allowedit']||!isset($_smarty_tpl->tpl_vars['element']->value['allowedit'])) {?>
												</a>
											<?php } else { ?>
												</p>
											<?php }?>
										<div class="parent-indent"></div>
									</div>
								</td>
							<?php } else { ?>
								<td class="list-level-0">
									<div class="td-content data-field-<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" data-sort=""><?php echo smarty_modifier_truncate(strip_tags($_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['key']->value]));?>
</div>
								</td>
							<?php }?>

						<?php } ?>
						
						</tr>
				       <?php } ?>
					</tbody>
				</table>				
			</div>					
			<fieldset></fieldset> <!-- all order changes made in zortable are saved here -->
		</div>		


			<?php if ($_smarty_tpl->tpl_vars['element']->value['tooltip']) {?><?php echo $_smarty_tpl->getSubTemplate ("cms_tooltip.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('valign'=>" middle"), 0);?>
<?php }?>
		</div>
	</div>
<?php }} ?>
