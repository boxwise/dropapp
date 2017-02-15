<?php /* Smarty version Smarty-3.1.18, created on 2017-02-15 15:48:07
         compiled from "/Users/maartenhunink/Sites/themarket/library/templates/cms_list.tpl" */ ?>
<?php /*%%SmartyHeaderCode:183434034058a45c176cb8b4-06845944%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '283b40546a458703e1a987ae8898b26425e51165' => 
    array (
      0 => '/Users/maartenhunink/Sites/themarket/library/templates/cms_list.tpl',
      1 => 1487166423,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '183434034058a45c176cb8b4-06845944',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'listconfig' => 0,
    'title' => 0,
    'settings' => 0,
    'translate' => 0,
    'button' => 0,
    'code' => 0,
    'key' => 0,
    'option' => 0,
    'listdata' => 0,
    'column' => 0,
    'data' => 0,
    'row' => 0,
    'listfooter' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_58a45c178c83d7_56094920',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58a45c178c83d7_56094920')) {function content_58a45c178c83d7_56094920($_smarty_tpl) {?><div class="row">
	<div class="col-sm-<?php echo $_smarty_tpl->tpl_vars['listconfig']->value['width'];?>
">
		<div class="row row-title">
			<div class="col-sm-12">
				<h1><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</h1>
			</div>
		</div>
		<div class="table-parent <?php if ($_smarty_tpl->tpl_vars['listconfig']->value['tree']) {?>list-tree<?php }?> <?php if ($_smarty_tpl->tpl_vars['listconfig']->value['allowsort']) {?>sortable<?php }?> <?php if ($_smarty_tpl->tpl_vars['listconfig']->value['allowmove']) {?>zortable<?php }?>" data-table="tablename" data-startlevel="<?php echo $_smarty_tpl->tpl_vars['listconfig']->value['allowmovefrom'];?>
" data-maxlevel="<?php if ($_smarty_tpl->tpl_vars['listconfig']->value['tree']) {?><?php echo $_smarty_tpl->tpl_vars['listconfig']->value['allowmoveto'];?>
<?php } else { ?>0<?php }?>" data-inheritvisibility="<?php echo $_smarty_tpl->tpl_vars['settings']->value['inheritvisibility'];?>
" data-action="<?php echo $_smarty_tpl->tpl_vars['listconfig']->value['thisfile'];?>
" data-saveonchange="1" <?php if ($_smarty_tpl->tpl_vars['listconfig']->value['maxheight']) {?>data-maxheight="<?php echo $_smarty_tpl->tpl_vars['listconfig']->value['maxheight'];?>
"<?php }?>>

			<div class="table-nav">
				<ul class="actions">
					<?php if ($_smarty_tpl->tpl_vars['listconfig']->value['allowselectall']) {?><li>
						<label class="btn btn-default btn-sm tooltip-this" data-toggle="tooltip" data-placement="top" title="<?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_list_selectall'];?>
" for="group-select-1"><input id="group-select-1" type="checkbox" class="group-select"></label>
					</li><?php }?>
					<li class="items-selected-dependent">
						<div class="btn-group">

							<?php if ($_smarty_tpl->tpl_vars['listconfig']->value['allowshowhide']) {?><button data-operation="show" class="start-operation btn btn-default btn-sm" href="#"><i class="fa glyphicon fa-eye"></i> <?php echo $_smarty_tpl->tpl_vars['listconfig']->value['show'];?>
</button>
							<button data-operation="hide" class="start-operation btn btn-default btn-sm" href="#"><i class="fa fa-eye-slash"></i> <?php echo $_smarty_tpl->tpl_vars['listconfig']->value['hide'];?>
</button><?php }?>

							<?php if ($_smarty_tpl->tpl_vars['listconfig']->value['allowdelete']) {?><button data-operation="delete" data-placement="top" data-title="<?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_list_confirm_title'];?>
" data-btn-ok-label="<?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_list_delete'];?>
" data-btn-cancel-label="<?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_list_confirm_cancel'];?>
" class="start-operation btn btn-sm confirm btn-danger" href="#"><i class="fa fa-trash-o"></i> <?php echo $_smarty_tpl->tpl_vars['listconfig']->value['delete'];?>
</button><?php }?>

							<?php if ($_smarty_tpl->tpl_vars['listconfig']->value['allowcopy']) {?><button data-operation="copy" data-placement="top" class="start-operation btn btn-sm btn-default" href="#"><i class="fa fa-copy"></i> <?php echo $_smarty_tpl->tpl_vars['listconfig']->value['copy'];?>
</button><?php }?>

							<?php  $_smarty_tpl->tpl_vars['button'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['button']->_loop = false;
 $_smarty_tpl->tpl_vars['code'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['listconfig']->value['button']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['button']->key => $_smarty_tpl->tpl_vars['button']->value) {
$_smarty_tpl->tpl_vars['button']->_loop = true;
 $_smarty_tpl->tpl_vars['code']->value = $_smarty_tpl->tpl_vars['button']->key;
?>
								<?php if ($_smarty_tpl->tpl_vars['button']->value['options']) {?>
									<div class="btn-group">
										<div type="button" class="btn btn-sm btn-default dropdown-toggle"><?php if ($_smarty_tpl->tpl_vars['button']->value['icon']) {?><i class="fa <?php echo $_smarty_tpl->tpl_vars['button']->value['icon'];?>
"></i> <?php }?><?php echo $_smarty_tpl->tpl_vars['button']->value['label'];?>
 <span class="caret"></span></div>
										<ul class="dropdown-menu pull-right button-multi" role="menu">
											<?php  $_smarty_tpl->tpl_vars['option'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['option']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['button']->value['options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['option']->key => $_smarty_tpl->tpl_vars['option']->value) {
$_smarty_tpl->tpl_vars['option']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['option']->key;
?>
												<li><a href="#" data-operation="<?php echo $_smarty_tpl->tpl_vars['code']->value;?>
" data-option="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" class="start-operation <?php if ($_smarty_tpl->tpl_vars['button']->value['confirm']) {?>confirm<?php }?>"><?php echo $_smarty_tpl->tpl_vars['option']->value;?>
</a></li>
											<?php } ?>
										</ul>
									</div>
								<?php } else { ?>
									<button data-operation="<?php if ($_smarty_tpl->tpl_vars['button']->value['link']) {?>none<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['code']->value;?>
<?php }?>" data-placement="top" data-title="<?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_list_confirm_title'];?>
" data-btn-ok-label="<?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_list_confirm_ok'];?>
" data-btn-cancel-label="<?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_list_confirm_cancel'];?>
" class="start-operation btn btn-sm <?php if ($_smarty_tpl->tpl_vars['button']->value['confirm']) {?>confirm<?php }?> btn-default <?php if ($_smarty_tpl->tpl_vars['button']->value['oneitemonly']) {?>one-item-only<?php }?>" href="<?php if ($_smarty_tpl->tpl_vars['button']->value['link']) {?><?php echo $_smarty_tpl->tpl_vars['button']->value['link'];?>
<?php } else { ?>#<?php }?>"><?php if ($_smarty_tpl->tpl_vars['button']->value['icon']) {?><i class="fa <?php echo $_smarty_tpl->tpl_vars['button']->value['icon'];?>
"></i> <?php }?><?php echo $_smarty_tpl->tpl_vars['button']->value['label'];?>
</button>
								<?php }?>
							<?php } ?>
						</div>
					</li>
					<?php if ($_smarty_tpl->tpl_vars['listconfig']->value['allowadd']) {?>
					<li>
						<a class="new-page item-add btn btn-sm btn-default" href="?action=<?php echo $_smarty_tpl->tpl_vars['listconfig']->value['edit'];?>
&origin=<?php echo $_smarty_tpl->tpl_vars['listconfig']->value['origin'];?>
"><i class="fa fa-plus"></i> <?php echo $_smarty_tpl->tpl_vars['listconfig']->value['add'];?>
</a>
					</li>
					<?php }?>
				</ul>
				<ul class="navigations pull-right list-unstyled">
<?php echo $_smarty_tpl->getSubTemplate ("cms_list_filter.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

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
										<button class="btn btn-sm btn-default" type="submit"><span class="fa fa-search"></span></button>
									</span>
								</div>
							</form>
						</li>
					<?php }?>
				</ul>
				<div class="fc"></div>
			</div>
			<div class="sticky-header-container">
				<table class="table list-<?php echo $_smarty_tpl->tpl_vars['listconfig']->value['origin'];?>
" <?php if ($_smarty_tpl->tpl_vars['listconfig']->value['sortlist']) {?>data-sortlist="<?php echo $_smarty_tpl->tpl_vars['listconfig']->value['sortlist'];?>
"<?php }?>>
				  	<thead>
					  	<tr>
						<?php  $_smarty_tpl->tpl_vars['column'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['column']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['listdata']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['column']->key => $_smarty_tpl->tpl_vars['column']->value) {
$_smarty_tpl->tpl_vars['column']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['column']->key;
?>
					  		<th><div <?php if ($_smarty_tpl->tpl_vars['column']->value['width']) {?>style="width:<?php echo $_smarty_tpl->tpl_vars['column']->value['width'];?>
px;"<?php }?>><?php echo $_smarty_tpl->tpl_vars['column']->value['label'];?>
</div></th>
						<?php } ?>
					  	</tr>
					</thead>
					<tbody>
				      <?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value) {
$_smarty_tpl->tpl_vars['row']->_loop = true;
?>
						<tr id="row-<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
" data-id="<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
" data-level="<?php echo $_smarty_tpl->tpl_vars['row']->value['level'];?>
" class="item <?php if (isset($_smarty_tpl->tpl_vars['row']->value['visible'])&&!$_smarty_tpl->tpl_vars['row']->value['visible']) {?>item-hidden<?php }?> level-<?php echo $_smarty_tpl->tpl_vars['row']->value['level'];?>

							<?php if (!$_smarty_tpl->tpl_vars['row']->value['preventedit']&&($_smarty_tpl->tpl_vars['listconfig']->value['allowedit'][$_smarty_tpl->tpl_vars['row']->value['level']]||!isset($_smarty_tpl->tpl_vars['listconfig']->value['allowedit']))) {?>item-clickable <?php } else { ?>item-nonclickable<?php }?>
							<?php if ($_smarty_tpl->tpl_vars['row']->value['preventdelete']) {?>item-nondeletable<?php }?>
							<?php if ($_smarty_tpl->tpl_vars['listconfig']->value['allowmove']&&$_smarty_tpl->tpl_vars['row']->value['level']>=$_smarty_tpl->tpl_vars['listconfig']->value['allowmovefrom']&&$_smarty_tpl->tpl_vars['row']->value['level']<=$_smarty_tpl->tpl_vars['listconfig']->value['allowmoveto']) {?>item-zortable<?php }?>
							<?php if ((is_array($_smarty_tpl->tpl_vars['listconfig']->value['allowselect'])&&$_smarty_tpl->tpl_vars['listconfig']->value['allowselect'][$_smarty_tpl->tpl_vars['row']->value['level']])||(!is_array($_smarty_tpl->tpl_vars['listconfig']->value['allowselect'])&&$_smarty_tpl->tpl_vars['listconfig']->value['allowselect'])) {?>item-selectable<?php }?>">
						<?php  $_smarty_tpl->tpl_vars['column'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['column']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['listdata']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['column']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['column']->key => $_smarty_tpl->tpl_vars['column']->value) {
$_smarty_tpl->tpl_vars['column']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['column']->key;
 $_smarty_tpl->tpl_vars['column']->index++;
 $_smarty_tpl->tpl_vars['column']->first = $_smarty_tpl->tpl_vars['column']->index === 0;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']["rows"]['first'] = $_smarty_tpl->tpl_vars['column']->first;
?>
							<?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['rows']['first']) {?>
								<td class="controls-front list-level-<?php echo $_smarty_tpl->tpl_vars['row']->value['level'];?>
 list-column-<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
">
									<div class="td-content">
										<div class="handle"><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span></div>
										<label class="item-select-label"><input class="item-select" type="checkbox"></label>
											<?php if (!$_smarty_tpl->tpl_vars['row']->value['preventedit']&&$_smarty_tpl->tpl_vars['listconfig']->value['allowedit'][$_smarty_tpl->tpl_vars['row']->value['level']]||!isset($_smarty_tpl->tpl_vars['listconfig']->value['allowedit'])) {?>
												<a class="td-content-field" href="?action=<?php echo $_smarty_tpl->tpl_vars['listconfig']->value['edit'];?>
&origin=<?php echo $_smarty_tpl->tpl_vars['listconfig']->value['origin'];?>
&id=<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
">
											<?php } else { ?>
												<p class="td-content-field">
											<?php }?>
											<?php echo $_smarty_tpl->getSubTemplate ("cms_list_".((string)$_smarty_tpl->tpl_vars['column']->value['type']).".tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

											<?php if (!$_smarty_tpl->tpl_vars['row']->value['preventedit']&&$_smarty_tpl->tpl_vars['listconfig']->value['allowedit'][$_smarty_tpl->tpl_vars['row']->value['level']]||!isset($_smarty_tpl->tpl_vars['listconfig']->value['allowedit'])) {?>
												</a>
											<?php } else { ?>
												</p>
											<?php }?>
										<div class="parent-indent"></div>
									</div>
								</td>
							<?php } else { ?>
								<td class="list-level-<?php echo $_smarty_tpl->tpl_vars['row']->value['level'];?>
 list-column-<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
">
									<div class="td-content"><?php echo $_smarty_tpl->getSubTemplate ("cms_list_".((string)$_smarty_tpl->tpl_vars['column']->value['type']).".tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
</div>
								</td>
							<?php }?>

						<?php } ?>

						</tr>
				       <?php } ?>
					</tbody>
					<?php if ($_smarty_tpl->tpl_vars['listfooter']->value) {?>
				  	<tfoot>
					  	<tr>
						<?php  $_smarty_tpl->tpl_vars['column'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['column']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['listfooter']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['column']->key => $_smarty_tpl->tpl_vars['column']->value) {
$_smarty_tpl->tpl_vars['column']->_loop = true;
?>
					  		<th><div><?php echo $_smarty_tpl->tpl_vars['column']->value;?>
</div></th>
						<?php } ?>
					  	</tr>
					</tfoot>
					<?php }?>
				</table>
			</div>

			<fieldset></fieldset> <!-- all order changes made in zortable are saved here -->
		</div>
	</div>
</div>
<?php }} ?>
