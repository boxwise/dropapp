<?php /* Smarty version Smarty-3.1.18, created on 2014-07-08 14:13:36
         compiled from "./templates/home.tpl" */ ?>
<?php /*%%SmartyHeaderCode:187519331453bbe070b1e3c6-89152531%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '62ef71fa9bffee4b2e45ea97bf20c2caac4cf263' => 
    array (
      0 => './templates/home.tpl',
      1 => 1402608670,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '187519331453bbe070b1e3c6-89152531',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'translate' => 0,
    'partners' => 0,
    'p' => 0,
    'news' => 0,
    'n' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_53bbe070c4ebb0_71524482',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53bbe070c4ebb0_71524482')) {function content_53bbe070c4ebb0_71524482($_smarty_tpl) {?>    <h1><a href="/wij-zijn"><?php echo $_smarty_tpl->tpl_vars['translate']->value['home_boilerplate'];?>
</a></h1>
<!--     <a href="/wij-zijn"><?php echo $_smarty_tpl->tpl_vars['translate']->value['home_leesmeeroverons'];?>
</a> </div>
 -->    <hr />
    <div class="partners-home"><?php  $_smarty_tpl->tpl_vars['p'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['p']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['partners']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['p']->key => $_smarty_tpl->tpl_vars['p']->value) {
$_smarty_tpl->tpl_vars['p']->_loop = true;
?><?php if ($_smarty_tpl->tpl_vars['p']->value['website']) {?><a href="<?php echo $_smarty_tpl->tpl_vars['p']->value['website'];?>
" target="_blank"><?php }?><img src="<?php echo $_smarty_tpl->tpl_vars['p']->value['thumb'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['p']->value['title'];?>
" align="middle" border="0"/><?php if ($_smarty_tpl->tpl_vars['p']->value['website']) {?></a><?php }?><?php } ?></div>
    <hr />
    <?php  $_smarty_tpl->tpl_vars['n'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['n']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['news']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['n']->key => $_smarty_tpl->tpl_vars['n']->value) {
$_smarty_tpl->tpl_vars['n']->_loop = true;
?>
      <div class="newsItem-home">
        <?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['n']->value['pagetitle'];?>
<?php $_tmp1=ob_get_clean();?><?php if ($_tmp1) {?><h2><a href="/article/<?php echo $_smarty_tpl->tpl_vars['n']->value['url'];?>
"><?php echo $_smarty_tpl->tpl_vars['n']->value['pagetitle'];?>
</a></h2><?php }?>
        <?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['n']->value['image'];?>
<?php $_tmp2=ob_get_clean();?><?php if ($_tmp2) {?><a href="/article/<?php echo $_smarty_tpl->tpl_vars['n']->value['url'];?>
"><img src="<?php echo $_smarty_tpl->tpl_vars['n']->value['thumb'];?>
" border="0"></a><?php }?>
        <p class="newsItem-pubdate"><?php echo $_smarty_tpl->tpl_vars['n']->value['pubdate'];?>
</p>
        <?php echo $_smarty_tpl->tpl_vars['n']->value['intro'];?>

        <br />
        <a href="/article/<?php echo $_smarty_tpl->tpl_vars['n']->value['url'];?>
"><?php if ($_smarty_tpl->tpl_vars['n']->value['ticketsurl']) {?><?php echo $_smarty_tpl->tpl_vars['translate']->value['readmoreandtickets'];?>
<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['translate']->value['readmore'];?>
<?php }?></a>
      </div>
      <hr />
    <?php } ?>
<?php }} ?>
