<?php /* Smarty version Smarty-3.1.18, created on 2015-09-15 17:14:27
         compiled from "./templates/form-20151109.tpl" */ ?>
<?php /*%%SmartyHeaderCode:35140039755f817f0a5fbc2-71252079%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0ce602cba1701e78a8de5ac267492c2afc953b1d' => 
    array (
      0 => './templates/form-20151109.tpl',
      1 => 1442330034,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '35140039755f817f0a5fbc2-71252079',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55f817f0b6bf24_63960907',
  'variables' => 
  array (
    'page' => 0,
    'translate' => 0,
    'formdata' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55f817f0b6bf24_63960907')) {function content_55f817f0b6bf24_63960907($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_replace')) include '/var/www/vhosts/global.zinnebeeld.nl/httpdocs/50-back/smarty/libs/plugins/modifier.replace.php';
?> <form action="index.php" method="post" data-lan="nl" id="orderform">

	<div class="form-section">
	    <div class="row">
		    <div class="col col-sm-8 col-md-9 col-lg-8">
				<div class="form-row">
					<table class="order-overview-table">
						<tr><td class="order-product"><?php echo $_smarty_tpl->tpl_vars['page']->value['menutitle'];?>
</td><td class="order-currency">&euro;</td><td class="order-price" data-price-original="<?php echo $_smarty_tpl->tpl_vars['page']->value['price'];?>
" data-price-2p="1100" data-price="<?php echo $_smarty_tpl->tpl_vars['page']->value['price'];?>
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
			    <div class="row">
			        <div class="col col-sm-6 form-row">
			        	<label for="company">bedrijf <span class="form-required-highlight">*</span></label>
			        	<input id="company" type="text" name="company" value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value['company'];?>
" class="form-adjust required">
			        </div>
			        <div class="col col-sm-6 form-row">
						<label for="function">functie <span class="form-required-highlight">*</span></label>
						<input id="function" type="text" name="function" value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value['function'];?>
" class="form-adjust required">
			        </div>
			    </div>
			    <div class="row">
			        <div class="col col-sm-6 form-row">
			        	<label for="email">e-mailadres <span class="form-required-highlight">*</span></label>
			        	<input id="email" type="email" name="email" value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value['email'];?>
" class="form-adjust required">
			        </div>
			        <div class="col col-sm-6 form-row">
						<label for="phone">telefoon</label>
						<input id="phone" type="text" name="phone" value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value['phone'];?>
" class="form-adjust">
			        </div>
			    </div>
		    </div>
		    <div class="col col-sm-4 col-md-3 col-lg-4">
			    <div class="form-side-info why-phone-and-email"><?php echo $_smarty_tpl->tpl_vars['page']->value['form-why-phone-and-email'];?>
</div>
		    </div>
	    </div>
	    <div class="row" style="margin-top: 10px;">
		    <div class="col col-sm-8 col-md-9 col-lg-8">
			     <div class="form-row">
					<div class="form-checkbox clearfix">
						<input type="checkbox" value="1" id="colleague" name="colleague" onclick="document.getElementById('voorcolleague').style.display = (this.checked==1?'block':'none');" />
						<label for="colleague" class="label-lg">Ik neem een collega mee</label>
					</div>
			    </div>
			    <!-- Dit deel is voor collega meenemen -->
			    <div id="voorcolleague" style="display:none; margin-bottom: 20px;">
				    <div class="row">
				        <div class="col col-sm-12 form-row">
				        	<h3 style="margin: 0px 0px 0px 0px;">Gegevens collega</h3>
				        </div>
				    </div>
				    <div class="row">
				        <div class="col col-sm-8 col-md-5 form-row">
				        	<label for="firstname_colleague">voornaam <span class="form-required-highlight">*</span></label>
				        	<input id="firstname_colleague" type="text" name="firstname_colleague" value="" class="form-adjust required">
				        </div>
				        <div class="col col-sm-4 col-md-2 form-row">
				        	<label for="inbetween_colleague">tussenvoegsel</label>
				        	<input id="inbetween_colleague" type="text" name="inbetween_colleague" value="" class="form-adjust">
				        </div>
				        <div class="col col-sm-12 col-md-5 form-row">
							<label for="lastname_colleague">achternaam <span class="form-required-highlight">*</span></label>
							<input id="lastname_colleague" type="text" name="lastname_colleague" value="" class="form-adjust required">
				        </div>
				    </div>
				    <div class="row">
				        <div class="col col-sm-6 form-row">
							<label for="function_colleague">functie <span class="form-required-highlight">*</span></label>
							<input id="function_colleague" type="text" name="function_colleague" value="" class="form-adjust required">
				        </div>
				    </div>
			    </div>
		    </div>
	    </div>
    </div>
	<div class="form-section-nomargin" style="margin-bottom: 20px;">
	    <div class="row">
		    <div class="col col-sm-8">
			    <h3>Welk bedrijfsvraagstuk van nu of de nabije toekomst vraagt om nieuwe perspectieven en kan de creativiteit en denkkracht van kunstenaars gebruiken?</h3>
		    </div>
	    </div>
	    <div class="row">
		    <div class="col col-sm-8 col-md-9 col-lg-8">
			    <div class="row">
			        <div class="col col-sm-12 form-row">
			        	<textarea id="remarks" type="text" name="remarks" class="form-adjust" style="height: 260px;"></textarea>
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

    <input type="hidden" name="products_id" value="<?php echo $_smarty_tpl->tpl_vars['page']->value['id'];?>
" />
    <input type="hidden" name="action" value="bestellen" />
    <input type="text" name="special" class="special" value="" />

 </form>







<?php }} ?>
