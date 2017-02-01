<?php /* Smarty version Smarty-3.1.18, created on 2015-08-27 15:09:53
         compiled from "/var/www/vhosts/cultuur-ondernemen.nl/httpdocs/site/templates/faq.tpl" */ ?>
<?php /*%%SmartyHeaderCode:155101975655df0c2156ff77-48639669%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'aa681f453854b7730432aff02212fff361f2a8e2' => 
    array (
      0 => '/var/www/vhosts/cultuur-ondernemen.nl/httpdocs/site/templates/faq.tpl',
      1 => 1440680114,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '155101975655df0c2156ff77-48639669',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'questions' => 0,
    'question' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55df0c215f3bb2_72758439',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55df0c215f3bb2_72758439')) {function content_55df0c215f3bb2_72758439($_smarty_tpl) {?>	<ul class="accordion faq">
		<?php  $_smarty_tpl->tpl_vars['question'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['question']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['questions']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['question']->key => $_smarty_tpl->tpl_vars['question']->value) {
$_smarty_tpl->tpl_vars['question']->_loop = true;
?>
		<li class="faq__item faq__item--togglebtn">
			<div class="faq__question"><div class="faq__togglebtn"></div><h3><?php echo $_smarty_tpl->tpl_vars['question']->value['question'];?>
</h3></div>
			<div class="faq__answer" style="display: none;">
				<?php echo $_smarty_tpl->tpl_vars['question']->value['answer'];?>

			</div>
		</li>
		<?php } ?>
	</ul><?php }} ?>
