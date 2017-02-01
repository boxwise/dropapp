<?php /* Smarty version Smarty-3.1.18, created on 2016-03-09 09:51:09
         compiled from "./templates/search.tpl" */ ?>
<?php /*%%SmartyHeaderCode:207018278256dfe3153d2425-91959437%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '692c3bc93f5ce1085954c77c1d0c047332ab27c2' => 
    array (
      0 => './templates/search.tpl',
      1 => 1457513456,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '207018278256dfe3153d2425-91959437',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_56dfe3155191f1_10362832',
  'variables' => 
  array (
    'searchtermbox' => 0,
    'translate' => 0,
    'numresults' => 0,
    'searchterm' => 0,
    'results' => 0,
    'result' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56dfe3155191f1_10362832')) {function content_56dfe3155191f1_10362832($_smarty_tpl) {?><section class="search">
  <div class="container">
    <div class="search-form">
      <form method="get">
        <div class="form-row clearfix">
          <input type="text" name="searchterm" id="searchterm" autocorrect="off" automplete="off" autocapitalize="off" spellcheck="false" class="search-input form-adjust" value="<?php echo $_smarty_tpl->tpl_vars['searchtermbox']->value;?>
" placeholder="<?php echo $_smarty_tpl->tpl_vars['translate']->value['search_placeholder'];?>
"/>
          <button type="submit" class="btn-submit-search"><span class="icon icon-search"></span></button>
        </div>
      </form>
    </div>

    <div class="search-result">
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
  </div>
</section>
<?php }} ?>
