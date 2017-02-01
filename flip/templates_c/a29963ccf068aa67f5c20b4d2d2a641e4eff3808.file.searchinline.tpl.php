<?php /* Smarty version Smarty-3.1.18, created on 2015-06-02 14:55:13
         compiled from "/var/www/vhosts/cultuur-ondernemen.nl/httpdocs/site/templates/searchinline.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1851471332556da7b1608014-29951634%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a29963ccf068aa67f5c20b4d2d2a641e4eff3808' => 
    array (
      0 => '/var/www/vhosts/cultuur-ondernemen.nl/httpdocs/site/templates/searchinline.tpl',
      1 => 1433248476,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1851471332556da7b1608014-29951634',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'numresults' => 0,
    'translate' => 0,
    'searchterm' => 0,
    'results' => 0,
    'result' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_556da7b16ef2a1_55726499',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556da7b16ef2a1_55726499')) {function content_556da7b16ef2a1_55726499($_smarty_tpl) {?><div class="search-result">
	<div class="search-count">
		<span class="numresults">
			<?php if ($_smarty_tpl->tpl_vars['numresults']->value==0) {?>
				<?php echo $_smarty_tpl->tpl_vars['translate']->value['search_noresults'];?>

				<span class="small"><?php echo $_smarty_tpl->tpl_vars['translate']->value['search_tooshort'];?>
</span>
			<?php } else { ?>
				<?php if ($_smarty_tpl->tpl_vars['numresults']->value==1) {?>
					<?php echo $_smarty_tpl->tpl_vars['translate']->value['search_onepage'];?>

				<?php } else { ?>
					<?php echo $_smarty_tpl->tpl_vars['numresults']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['translate']->value['search_pages'];?>

				<?php }?>
				<?php echo $_smarty_tpl->tpl_vars['searchterm']->value;?>

			<?php }?>
		</span>
	</div>
	<div class="search-results">
	<?php  $_smarty_tpl->tpl_vars['result'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['result']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['results']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['result']->key => $_smarty_tpl->tpl_vars['result']->value) {
$_smarty_tpl->tpl_vars['result']->_loop = true;
?>
		<?php if ($_smarty_tpl->tpl_vars['result']->value['title']) {?>
		<div class="search-result-item">
			<h1><a href="/<?php echo $_smarty_tpl->tpl_vars['result']->value['url_prefix'];?>
<?php echo $_smarty_tpl->tpl_vars['result']->value['url'];?>
"><?php echo $_smarty_tpl->tpl_vars['result']->value['title'];?>
</a></h1>									
			<p><?php if ($_smarty_tpl->tpl_vars['result']->value['function']) {?><span class="meta"><?php echo $_smarty_tpl->tpl_vars['result']->value['function'];?>
</span><?php }?><?php echo $_smarty_tpl->tpl_vars['result']->value['desc'];?>
 </p>
		</div>
		<?php }?>
	<?php } ?>
	</div>
</div>
<?php }} ?>
