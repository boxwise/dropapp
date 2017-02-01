<?php /* Smarty version Smarty-3.1.18, created on 2015-10-05 15:07:48
         compiled from "./templates/form-aanmelden.tpl" */ ?>
<?php /*%%SmartyHeaderCode:196330351055d19d054d9e73-83926699%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '285fe825c3af5971e5e9250f3d771825d0c7c106' => 
    array (
      0 => './templates/form-aanmelden.tpl',
      1 => 1444050465,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '196330351055d19d054d9e73-83926699',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55d19d055b4105_89785527',
  'variables' => 
  array (
    'page' => 0,
    'formdata' => 0,
    'translate' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55d19d055b4105_89785527')) {function content_55d19d055b4105_89785527($_smarty_tpl) {?> <form action="index.php" method="post" id="orderform" data-lan="nl">

    <div class="form-section-nomargin">
    	<?php if ($_smarty_tpl->tpl_vars['page']->value['artist_check']) {?>
	    <div class="row">
		    <div class="col col-sm-8 col-md-9 col-lg-8">
				<div class="form-row">
				     <div class="form-checkbox clearfix">
						<input type="checkbox" value="1" <?php if ($_smarty_tpl->tpl_vars['page']->value['artist_nobtw']) {?>class="discount"<?php }?> id="independent" name="independent" <?php if ($_smarty_tpl->tpl_vars['formdata']->value['independent']==1) {?>checked<?php }?> />
						<label for="independent" class="label-lg">Ik ben zelfstandig kunstenaar of creatief</label>
					</div>
				</div>
		    </div>
		</div>
		<?php }?>
	    <div class="row">
		    <div class="col col-sm-12">
			    <h3>Mijn gegevens</h3>
		    </div>
	    </div>
	    <div class="row">
		    <div class="col col-sm-8 col-md-9 col-lg-8">
			    <div class="row">
			        <div class="col col-sm-8 col-md-5 form-row">
			        	<label for="firstname">voornaam <span class="form-required-highlight">*</span></label>
			        	<input id="firstname" type="text" name="firstname" value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value['firstname'];?>
" class="form-adjust required">
			        </div>
			        <div class="col col-sm-4 col-md-2 form-row">
			        	<label for="inbetween">tussenvoegsel</label>
			        	<input id="inbetween" type="text" name="inbetween" value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value['inbetween'];?>
" class="form-adjust">
			        </div>
			        <div class="col col-sm-12 col-md-5 form-row">
						<label for="lastname">achternaam <span class="form-required-highlight">*</span></label>
						<input id="lastname" type="text" name="lastname" value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value['lastname'];?>
" class="form-adjust required">
			        </div>
			    </div>
			     <div class="row row-profession">
			        <div class="col col-sm-12 form-row">
			        	<label for="profession">beroep</label>
			        	<input id="profession" type="text" name="profession" value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value['profession'];?>
" class="form-adjust">
			        </div>
			    </div>
			    <div class="row row-company">
			        <div class="col col-sm-12 form-row">
			        	<label for="company">bedrijf/organisatie</label>
			        	<input id="company" type="text" name="company" value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value['company'];?>
" class="form-adjust">
			        </div>
			    </div>
			    <div class="row">
			        <div class="col col-sm-6 form-row">
			        	<label for="email">e-mailadres <span class="form-required-highlight">*</span></label>
			        	<input id="email" type="email" name="email" value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value['email'];?>
" class="form-adjust required">
			        </div>
			        <div class="col col-sm-6 form-row">
						<label for="phone">telefoon <span class="form-required-highlight">*</span></label>
						<input id="phone" type="text" name="phone" value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value['phone'];?>
" class="form-adjust required">
			        </div>
			    </div>
			    <div class="row">
			        <div class="col col-sm-12 form-row">
			        	<label for="street">straatnaam en huisnummer <span class="form-required-highlight">*</span></label>
			        	<input id="street" type="text" name="street" value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value['street'];?>
" class="form-adjust required">
			        </div>
			    </div>
		    </div>
		    <div class="col col-sm-4 col-md-3 col-lg-4">
			    <div class="form-side-info"><?php echo $_smarty_tpl->tpl_vars['page']->value['form-why-phone-and-email'];?>
</div>
		    </div>
	    </div>
    </div>
    <div class="form-section">
	    <div class="row">
		    <div class="col col-sm-8 col-md-9 col-lg-8">
			    <div class="row">
			        <div class="col col-sm-3 form-row">
			        	<label for="postcode">postcode <span class="form-required-highlight">*</span></label>
			        	<input id="postcode" type="text" name="postcode" value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value['postcode'];?>
" class="form-adjust required">
			        </div>
			        <div class="col col-sm-9 form-row">
						<label for="city">plaatsnaam <span class="form-required-highlight">*</span></label>
						<input id="city" type="text" name="city" value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value['city'];?>
" class="form-adjust required">
			        </div>
			    </div>
		    </div>
	    </div>
    </div>
	<div class="form-section">
	    <div class="row">
		    <div class="col col-sm-8 col-md-9 col-lg-8">
			     <div class="form-row">
				     <div class="form-checkbox clearfix">
						<input type="checkbox" value="1" id="savedata" name="savedata" <?php if ($_smarty_tpl->tpl_vars['formdata']->value) {?>checked<?php }?> />
						<label for="savedata" class="label-lg">Bewaar mijn gegevens voor een volgende keer<span class="label-extra-info"><?php echo $_smarty_tpl->tpl_vars['translate']->value['save_data_form'];?>
</span></label>
					</div>
					<div class="form-checkbox clearfix">
						<input type="checkbox" value="1" id="newsletter" name="newsletter" />
						<label for="newsletter" class="label-lg">Ik meld me aan voor de nieuwsbrief van Cultuur+Ondernemen</label>
					</div>
			    </div>
		    </div>
	    </div>
	</div>
   	<div class="form-section">
	    <div class="row">
		    <div class="col col-sm-12">
				<div class="payment-button"><button class="btn btn-blue"><span class="btn-text">Aanmelden</span><span class="icon icon-arrow-right"></span></button></div>
		    </div>
	    </div>
    </div>
    <div class="form-section">
	    <div class="row">
		    <div class="col col-sm-12">
				<span class="form-required-highlight">* invullen verplicht</span>
		    </div>
	    </div>
    </div>

    <input type="hidden" name="action" value="mailen" />
    <input type="hidden" name="products_id" value="<?php echo $_smarty_tpl->tpl_vars['page']->value['id'];?>
" />
    <input type="text" name="special" class="special" value="" />

 </form>







<?php }} ?>
