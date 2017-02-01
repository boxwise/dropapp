<?php /* Smarty version Smarty-3.1.18, created on 2015-09-08 01:29:15
         compiled from "./templates/form-orientatiegesprek-en.tpl" */ ?>
<?php /*%%SmartyHeaderCode:126291119055720b888b17d9-06233756%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd108fed500ef9530c3538059c5127779d77b3313' => 
    array (
      0 => './templates/form-orientatiegesprek-en.tpl',
      1 => 1440680124,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '126291119055720b888b17d9-06233756',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55720b889756b3_99702535',
  'variables' => 
  array (
    'formdata' => 0,
    'translate' => 0,
    'page' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55720b889756b3_99702535')) {function content_55720b889756b3_99702535($_smarty_tpl) {?> <form action="index.php" method="post" data-lan="en" id="orderform">   
    
    <div class="form-section">
	    <div class="row">
		    <div class="col col-sm-8 col-md-9 col-lg-8">     
			    <div class="row">
			        <div class="col col-sm-12 form-row">
				        <h3>My question for Cultuur+Ondernemen</h3>
			        </div>
			    </div>
		    </div>
	    </div>
	    <div class="row">
		    <div class="col col-sm-8 col-md-9 col-lg-8">     
			    <div class="row">
			        <div class="col col-sm-12 form-row">
			        	<label for="question">My question <span class="form-required-highlight">*</span></label>
			        	<textarea id="question" type="text" name="question" class="form-adjust required"></textarea>
			        </div>
			    </div>
		    </div>
		    <div class="col col-sm-4 col-md-3 col-lg-2">  
			    <div class="form-side-info">What is your question about? Based on your question we will pick the right conversational partner for you.</div>
		    </div>   
	    </div>
    </div>
    <div class="form-section">
	    <div class="row">
		    <div class="col col-sm-8 col-md-9 col-lg-8">     
			    <div class="row">
			        <div class="col col-sm-12 form-row">
			        	<h3>My data</h3>
					</div>
			    </div>
			    <div class="row">
			        <div class="col col-sm-8 col-md-5 form-row">
			        	<label for="firstname">first name <span class="form-required-highlight">*</span></label>
			        	<input id="firstname" type="text" name="firstname" value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value['firstname'];?>
" class="form-adjust required">
			        </div>
			        <div class="col col-sm-4 col-md-2 form-row">
			        	<label for="inbetween">inbetween</label>
			        	<input id="inbetween" type="text" name="inbetween" value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value['inbetween'];?>
" class="form-adjust">
			        </div>
			        <div class="col col-sm-12 col-md-5 form-row">
						<label for="lastname">last name <span class="form-required-highlight">*</span></label>
						<input id="lastname" type="text" name="lastname" value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value['lastname'];?>
" class="form-adjust required">
			        </div>
			    </div>
			    <div class="row">
			        <div class="col col-sm-6 form-row">
			        	<label for="email">e-mailaddress <span class="form-required-highlight">*</span></label>
			        	<input id="email" type="email" name="email" value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value['email'];?>
" class="form-adjust required">
			        </div>
			        <div class="col col-sm-6 form-row">
						<label for="phone">telephone <span class="form-required-highlight">*</span></label>
						<input id="phone" type="text" name="phone" value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value['phone'];?>
" class="form-adjust required">
			        </div>
			    </div>
			    <div class="row">
			        <div class="col col-sm-12 form-row">
			        	<label for="website">website</label>
			        	<input id="website" type="text" name="website" value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value['website'];?>
" class="form-adjust">
			        </div>
			    </div> 			    
		    </div>
	    </div>
    </div>
    <div class="form-section">
    	<div class="row">
	    	<div class="col col-sm-8 col-md-9 col-lg-8">	    
			    <div class="row">
			        <div class="col col-sm-12 form-row">

				        <label for="type">background <span class="form-required-highlight">*</span></label>
				        <div class="form-select">
							<select class="select required" id="type" name="type" onchange="
				        		document.getElementById('voorzelfstandig').style.display = (this.selectedIndex==1?'block':'none');
				        		document.getElementById('voorbedrijf').style.display = (this.selectedIndex==2?'block':'none');">
								<option value="">please choose</option>
								<option value="zelfstandig">I am independent</option>
								<option value="bedrijf">I represent a company / institution</option>
							</select>
						</div>
			        </div>
			    </div> 
			    <!-- Dit deel is voor zelfstandig -->
			    <div id="voorzelfstandig" style="display:none">
				    <div class="row">
				        <div class="col col-sm-12 form-row">
				        	<label for="city">city/town <span class="form-required-highlight">*</span></label>
				        	<input id="city" type="text" name="city" value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value['city'];?>
" class="form-adjust required">
				        </div>
				    </div> 			    
				    <div class="row">
				        <div class="col col-sm-12 form-row">
				        	<label for="function">occupation</label>
				        	<input id="function" type="text" name="function" value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value['function'];?>
" class="form-adjust">
				        </div>
				    </div> 			    
			    </div>
				<!-- Tot hier voor zelfstandig -->
				
			    <!-- Dit deel is voor bedrijf -->
			    <div id="voorbedrijf" style="display:none">
				    <div class="row">
				        <div class="col col-sm-12 form-row">
				        	<label for="company">company/organisation <span class="form-required-highlight">*</span></label>
				        	<input id="company" type="text" name="company" value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value['company'];?>
" class="form-adjust required">
				        </div>
				    </div> 			    
				    <div class="row">
				        <div class="col col-sm-12 form-row">
				        	<label for="function">function</label>
				        	<input id="function" type="text" name="function" value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value['function'];?>
" class="form-adjust">
				        </div>
				    </div> 			    
				    <div class="row">
				        <div class="col col-sm-12 form-row">
				        	<label for="city2">city/town <span class="form-required-highlight">*</span></label>
				        	<input id="city2" type="text" name="city2" value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value['city'];?>
" class="form-adjust required">
				        </div>
				    </div> 			    
				    <div class="row">
				        <div class="col col-sm-12 form-row">
					        
					        <label for="orgtype">type of organisation</label>
					        <div class="form-select">
								<select class="select" id="orgtype" name="orgtype">
									<option>Please choose</option>
						        	<option value="Cultural institution">Cultural institution</option>
						        	<option value="Government">Government</option>
						        	<option value="Fund">Fund</option>
						        	<option value="Branch Organization">Branch Organization</option>
						        	<option value="Public sector">Public sector</option>
						        	<option value="Healthcare and welfare">Healthcare and welfare</option>
						        	<option value="Education">Education</option>
						        	<option value="Business">Business</option>
								</select>
							</div>
				        </div>
				    </div> 
			    </div>
				<!-- Tot hier voor bedrijf -->
		    </div> 
	    </div>
    </div>
	<div class="form-section"> 
	    <div class="row">
		    <div class="col col-sm-8 col-md-9 col-lg-8">
			     <div class="form-row">
				     <div class="form-checkbox clearfix">
						<input type="checkbox" value="1" id="savedata" name="savedata" <?php if ($_smarty_tpl->tpl_vars['formdata']->value) {?>checked<?php }?> />
						<label for="savedata" class="label-lg">Remember my data for next time<span class="label-extra-info"><?php echo $_smarty_tpl->tpl_vars['translate']->value['save_data_form_english'];?>
</span></label>						
					</div>
			    </div>
		    </div>
	    </div>
	</div>
   	<div class="form-section">
	    <div class="row">
		    <div class="col col-sm-12">
				<div class="payment-button"><button class="btn btn-blue"><span class="btn-text">Send request</span><span class="icon icon-arrow-right"></span></button></div>
		    </div>
	    </div>
    </div>
    <div class="form-section">
	    <div class="row">
		    <div class="col col-sm-12">
				<span class="form-required-highlight">* required</span>
		    </div>
	    </div>
    </div>
    
    <input type="hidden" name="products_id" value="<?php echo $_smarty_tpl->tpl_vars['page']->value['id'];?>
" />
    <input type="hidden" name="action" value="mailen" />
    <input type="text" name="special" class="special" value="" />

 </form>







<?php }} ?>
