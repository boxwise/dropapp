<?php /* Smarty version Smarty-3.1.18, created on 2016-06-01 12:48:50
         compiled from "./templates/faq21.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2065502786574ebd927aa8b4-08803241%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '87037778479085a708008e4f2fdf598829fa6e39' => 
    array (
      0 => './templates/faq21.tpl',
      1 => 1464778126,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2065502786574ebd927aa8b4-08803241',
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
  'unifunc' => 'content_574ebd9281d233_74687039',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_574ebd9281d233_74687039')) {function content_574ebd9281d233_74687039($_smarty_tpl) {?>	<ul class="accordion faq">
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
