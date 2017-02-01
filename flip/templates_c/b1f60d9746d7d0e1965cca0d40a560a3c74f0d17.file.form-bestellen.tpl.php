<?php /* Smarty version Smarty-3.1.18, created on 2016-10-06 11:16:01
         compiled from "./templates/form-bestellen.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1271743179556dac7f67f9e1-44104261%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b1f60d9746d7d0e1965cca0d40a560a3c74f0d17' => 
    array (
      0 => './templates/form-bestellen.tpl',
      1 => 1475745358,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1271743179556dac7f67f9e1-44104261',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_556dac7f77de37_13405201',
  'variables' => 
  array (
    'page' => 0,
    'formdata' => 0,
    'translate' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556dac7f77de37_13405201')) {function content_556dac7f77de37_13405201($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_replace')) include '/var/www/vhosts/global.zinnebeeld.nl/httpdocs/50-back/smarty/libs/plugins/modifier.replace.php';
?> <form action="index.php" method="post" id="orderform" data-lan="nl">

  <?php if ($_smarty_tpl->tpl_vars['page']->value['url']=='zakelijke-gids') {?>
	<div class="form-section-nomargin">
	  <div class="row">
		  <div class="col col-sm-8 col-md-9 col-lg-8">
				<div class="form-row">
						<label for="quantity" class="label-lg">Aantal zakelijke gidsen:</label> &nbsp;
            <select id="quantity" name="quantity">   
              <option<?php if ($_smarty_tpl->tpl_vars['formdata']->value['quantity']==1) {?> selected<?php }?> value="1">1</option>
              <option<?php if ($_smarty_tpl->tpl_vars['formdata']->value['quantity']==2) {?> selected<?php }?> value="2">2</option>
              <option<?php if ($_smarty_tpl->tpl_vars['formdata']->value['quantity']==3) {?> selected<?php }?> value="3">3</option>
              <option<?php if ($_smarty_tpl->tpl_vars['formdata']->value['quantity']==4) {?> selected<?php }?> value="4">4</option>
              <option<?php if ($_smarty_tpl->tpl_vars['formdata']->value['quantity']==5) {?> selected<?php }?> value="5">5</option>
            </select>
				</div>
				<div class="form-row">
          Wilt u meer dan 5 exemplaren bestellen, neem dan contact op via 020 - 535 2500 of <a href="mailto:info@cultuur-ondernemen.nl" style="white-space: nowrap;">info@cultuur-ondernemen.nl</a>. 
        </div>
		  </div>
		</div>
	</div>
  <br />            
  <?php }?>
   
	<?php if ($_smarty_tpl->tpl_vars['page']->value['artist_check']) {?>
	<div class="form-section-nomargin">
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
	</div>
	<?php }?>
  
	<?php if ($_smarty_tpl->tpl_vars['page']->value['nvtc']) {?>
	<div class="form-section-nomargin">
	    <div class="row">
		    <div class="col col-sm-8 col-md-9 col-lg-8">
				<div class="form-row">
				     <div class="form-checkbox clearfix">
						<input type="checkbox" value="1" id="nvtc" name="nvtc" <?php if ($_smarty_tpl->tpl_vars['formdata']->value['nvtc']==1) {?>checked<?php }?> />
						<label for="nvtc" class="label-lg">Ik ben lid van de NVTC</label>
					</div>
				</div>
		    </div>
		</div>
	</div>
	<?php }?>

	<div class="form-section">
	    <div class="row">
		    <div class="col col-sm-8 col-md-9 col-lg-8">
				<div class="form-row">
					<table class="order-overview-table">
						<tr><td class="order-product"><?php echo $_smarty_tpl->tpl_vars['page']->value['menutitle'];?>
</td><td class="order-currency">&euro;</td><td class="order-price" data-price="<?php echo $_smarty_tpl->tpl_vars['page']->value['price'];?>
" data-price-artist="<?php echo $_smarty_tpl->tpl_vars['page']->value['price_artist'];?>
"><?php echo sprintf("%.2f",$_smarty_tpl->tpl_vars['page']->value['price']);?>
</td></tr>
						<?php if ($_smarty_tpl->tpl_vars['page']->value['hasdiscount']) {?><tr class="order-discount"><td><?php echo $_smarty_tpl->tpl_vars['translate']->value['discount_yourdiscont'];?>
 <span class="order-discount-label-perc"></span></td><td class="order-currency">&euro;</td><td class="order-discount-total" data-amount=""></td></tr><?php }?>
						<tr><td class="order-vat" data-vat="<?php echo $_smarty_tpl->tpl_vars['page']->value['btw'];?>
">BTW <?php echo $_smarty_tpl->tpl_vars['page']->value['btw'];?>
%</td><td class="order-currency">&euro;</td><td class="order-vat-total"></td></tr>
						<tr><td colspan="3"><div class="order-total-divider-line"></div></td></tr>
						<tr class="order-total"><td>Totaal</td><td class="order-currency">&euro;</td><td class="order-price-total"></td></tr>
					</table>
				</div>
		    </div>
		    <div class="col col-sm-4 col-md-3 col-lg-4">
			    <div class="form-side-info btw"><?php ob_start();?><?php echo ($_smarty_tpl->tpl_vars['page']->value['btw']).('%');?>
<?php $_tmp1=ob_get_clean();?><?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['page']->value['btw-of-geen-btw'],'%%',$_tmp1);?>
</div>
				<div class="form-side-info"><?php echo $_smarty_tpl->tpl_vars['translate']->value['termsandconditions'];?>
</div>
		    </div>
		</div>
	</div>

	<?php if ($_smarty_tpl->tpl_vars['page']->value['hasdiscount']) {?>
    <div class="form-section-nomargin">
	    <div class="row discount-opener">
		    <div class="col col-sm-12 col-md-12 col-lg-12">
			    <div class="row">
				    <div class="col col-md-12 col-sm-12">
					    <p><a href="#"><?php echo $_smarty_tpl->tpl_vars['translate']->value['discount_codeactivator'];?>
</a></p>
				    </div>
			    </div>
		    </div>
	    </div>
	    <div class="row discount">
		    <div class="col col-sm-8 col-md-9 col-lg-8">
			    <div class="row">
				    <div class="col col-md-5 col-sm-8">
					    <h3 class="discount"><?php echo $_smarty_tpl->tpl_vars['translate']->value['discount_yourcode'];?>
 </h3>
				    </div>
			        <div class="col col-sm-4 col-md-7 form-row">
			        	<input id="discountcode" type="text" name="discountcode" value="" class="form-adjust"><a href="#" title="checken" class="discount-checker"><span class="icon icon-cycle"></span></a><span class="icon icon-done"></span>
			        </div>
			        <div class="col col-md-12 col-sm-12">
				        <label class="error" id="discountmessage"></label>
			        </div>
			    </div>
		    </div>
	    </div>
    </div>
    <?php }?>

    <div class="form-section-nomargin">
	    <div class="row">
		    <div class="col col-sm-12">
			    <h3>Mijn gegevens</h3>
			    <div class="form-required-highlight">* <span>invullen verplicht</span></div>
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
			    <div class="row row-company">
			        <div class="col col-sm-12 form-row">
			        	<label for="company">bedrijf/organisatie</label>
			        	<input id="company" type="text" name="company" value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value['company'];?>
" class="form-adjust">
			        </div>
			    </div>
			     <div class="row row-profession">
			        <div class="col col-sm-12 form-row">
			        	<label for="profession">beroep</label>
			        	<input id="profession" type="text" name="profession" value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value['profession'];?>
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
			    <div class="form-side-info why-phone-and-email"><?php echo $_smarty_tpl->tpl_vars['page']->value['form-why-phone-and-email'];?>
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
				<div class="payment-button"><button class="btn btn-blue"><span class="btn-text">Door naar betalen</span><span class="icon icon-arrow-right"></span></button></div>
				<div class="payment-options">
					<div class="payment-options-header"><?php echo $_smarty_tpl->tpl_vars['translate']->value['payment-options'];?>
</div>
					<div class="payment-options-logos">
						<span class="payment-option payment-option-ideal">iDEAL</span><span class="payment-option payment-option-mastercard">Mastercard</span><span class="payment-option payment-option-visa">Visa</span>
					</div>
				</div>
		    </div>
	    </div>
    </div>

    <input type="hidden" name="action" value="bestellen" />
    <input type="hidden" name="products_id" value="<?php echo $_smarty_tpl->tpl_vars['page']->value['id'];?>
" />
    <input type="text" name="special" class="special" value="" />

 </form>







<?php }} ?>
