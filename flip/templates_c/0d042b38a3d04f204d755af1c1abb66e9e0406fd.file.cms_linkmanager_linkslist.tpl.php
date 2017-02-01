<?php /* Smarty version Smarty-3.1.18, created on 2015-08-18 11:54:51
         compiled from "/var/www/vhosts/global.zinnebeeld.nl/httpdocs/50-back/templates/cms_linkmanager_linkslist.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1773775521556dd1c3e7d608-41112631%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0d042b38a3d04f204d755af1c1abb66e9e0406fd' => 
    array (
      0 => '/var/www/vhosts/global.zinnebeeld.nl/httpdocs/50-back/templates/cms_linkmanager_linkslist.tpl',
      1 => 1439565357,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1773775521556dd1c3e7d608-41112631',
  'function' => 
  array (
    'linkslist' => 
    array (
      'parameter' => 
      array (
        'level' => 0,
        'prefix' => '/',
      ),
      'compiled' => '',
    ),
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_556dd1c4074de8_75677551',
  'variables' => 
  array (
    'level' => 0,
    'data' => 0,
    'item' => 0,
    'prefix' => 0,
    'title' => 0,
    'links' => 0,
  ),
  'has_nocache_code' => 0,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556dd1c4074de8_75677551')) {function content_556dd1c4074de8_75677551($_smarty_tpl) {?>	<?php if (!function_exists('smarty_template_function_linkslist')) {
    function smarty_template_function_linkslist($_smarty_tpl,$params) {
    $saved_tpl_vars = $_smarty_tpl->tpl_vars;
    foreach ($_smarty_tpl->smarty->template_functions['linkslist']['parameter'] as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);};
    foreach ($params as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);}?>
	<ul class="level<?php echo $_smarty_tpl->tpl_vars['level']->value;?>
">
		<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
			<?php if (is_array($_smarty_tpl->tpl_vars['item']->value['sub'])) {?>
				<li><a class="linkmanager-link" data-url="<?php echo $_smarty_tpl->tpl_vars['prefix']->value;?>
<?php echo $_smarty_tpl->tpl_vars['item']->value['url'];?>
" href="<?php echo $_smarty_tpl->tpl_vars['prefix']->value;?>
<?php echo $_smarty_tpl->tpl_vars['item']->value['url'];?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value['title'];?>
</a></li>
				<?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['level']->value;?>
<?php $_tmp1=ob_get_clean();?><?php smarty_template_function_linkslist($_smarty_tpl,array('data'=>$_smarty_tpl->tpl_vars['item']->value['sub'],'level'=>$_tmp1+1,'prefix'=>$_smarty_tpl->tpl_vars['prefix']->value));?>

			<?php } else { ?>
				<li><a class="linkmanager-link" data-url="<?php echo $_smarty_tpl->tpl_vars['prefix']->value;?>
<?php echo $_smarty_tpl->tpl_vars['item']->value['url'];?>
" href="<?php echo $_smarty_tpl->tpl_vars['prefix']->value;?>
<?php echo $_smarty_tpl->tpl_vars['item']->value['url'];?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value['title'];?>
</a></li>
			<?php }?>
		<?php } ?>
	</ul>
	<?php $_smarty_tpl->tpl_vars = $saved_tpl_vars;
foreach (Smarty::$global_tpl_vars as $key => $value) if(!isset($_smarty_tpl->tpl_vars[$key])) $_smarty_tpl->tpl_vars[$key] = $value;}}?>


	<div class="links-frame">	
		<h3><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</h3>	
		<?php smarty_template_function_linkslist($_smarty_tpl,array('data'=>$_smarty_tpl->tpl_vars['links']->value,'prefix'=>$_smarty_tpl->tpl_vars['prefix']->value));?>

	</div>	<?php }} ?>
