$(function() {
	$('.bar').click(function(e){
		$('body').toggleClass('different-view');
	})

})
function cms_form_valutaCO(field) {
	value = $('#field_'+field).val();
	if(value.substr(0,2)=='€ ') value = value.replace(/\./g,'');
	if(value.substr(0,1)=='€') value = value.substr(1);
	if(value.substr(0,1)==' ') value = value.substr(1);
	value = parseFloat(value.replace(/,/g,'.')).toFixed(2).replace(/\./g,',');
	if(value=='NaN') value = '';
	if(value == '') {
		$('#field_'+field).val();
	} else {
		$('#field_'+field).val('€ '+PointPerThousand(value));
	}
}

function toggleDiscountFields() {

	var selectedVal = $('#field_discount_type').find(":selected").val();

	$('#div_discount_amount').addClass('hidden');
	$('#div_discount_perc').addClass('hidden');
	$('#div_discount_'+selectedVal).removeClass('hidden');
}


if($('#field_people_id').val() != undefined){
	eval(($('#field_people_id').attr('onchange')));
}

function selectFamily(field){
	value = $('#field_'+field).val();
	if(value){
		if(value != $('#div_purch').data('listid')){
			$('#div_purch').hide();
		}
		$('#form-submit').prop('disabled', true);
		$('#field_'+field).prop('disabled', true);
		$('body').addClass('loading');
		$.ajax({
			type: 'post',
			url: 'include/purchase.php',
			data:
			{
				people_id: value,
			},
			dataType: 'json',
			success: function(result){
				url = window.location;
				window.history.pushState("purchase", "Purchase", url.toString().split("?")[0] + "?action=purchase&people_id="+value);
				if(result.success){
					$('#ajax-content').html(result.htmlcontent);												
					initiateList();
					$('#ajax-aside').html(result.htmlaside);
					$('.not_enough_coins').removeClass('not_enough_coins');
					if($('#field_product_id').val()){
						calcCosts('count');
					}
					$('#field_'+field).prop('disabled', false);
					$('body').removeClass('loading');
				}
				if(result.message){
					var n = noty({
						text: result.message,
						type: (result.success ? 'success' : 'error')
					});
				}
			},
			error: function(result){
				var n = noty({
					text: 'Something went wrong, maybe the internet connection is a bit choppy',
					type: 'error'
				});
			}
		});
	} else {
		$('#dropcredit').data({'dropCredit': 0});
		if($('#product_id_selected').is(':visible')){
			calcCosts('count');
		}
		$('#people_id_selected').addClass('hidden');
	}
}

function getProductValue(field){
	value = $('#field_'+field).val();
	if(value){
		$('#form-submit').prop('disabled', true);
		$('#field_'+field).prop('disabled', true);
		$('body').addClass('loading');
		$.ajax({
			type: 'post',
			url: 'ajax.php?file=getproductvalue',
			data:
			{
				product_id: value,
			},
			dataType: 'json',
			success: function(result){
				if(result.success){
					$('#ajax-aside').data({'productValue': result.drops});
					calcCosts('count');
					$('#field_'+field).prop('disabled', false);
					$('body').removeClass('loading');
				}
				if(result.message){
					var n = noty({
						text: result.message,
						type: (result.success ? 'success' : 'error')
					});
				}
			},
			error: function(result){
				var n = noty({
					text: 'Something went wrong, maybe the internet connection is a bit choppy',
					type: 'error'
				});
			}
		});		
	} else {
		$('#field_'+field).prop('disabled', false);
	}	
}
function calcCosts(field){
	amount = ($('#field_'+field).val() ? $('#field_'+field).val() : 1);
	productvalue = $('#ajax-aside').data('productValue');
	dropcredit = $('#dropcredit').data('dropCredit');
	totalprice = amount * productvalue;
	$('#productvalue').text(totalprice);
	$('#product_id_selected').removeClass('hidden');
	
	if(dropcredit >= totalprice){
		$('#form-submit').prop('disabled', false);
		$('.aside-form').removeClass('not_enough_coins');
	} else {
		$('#form-submit').prop('disabled', true);
		$('.aside-form').addClass('not_enough_coins');
	}
}

function correctDrops(first, second){
	$('#row-'+first.id+' .list-column-drops .td-content').text(first.value);
	$('#row-'+second.id+' .list-column-drops .td-content').text(second.value);
}

function getSizes(){
	$('#field_product_id, #field_size_id').prop('disabled', true);
	value = $('#field_product_id').val();
	$('body').addClass('loading');
	$.ajax({
		type: 'post',
		url: 'ajax.php?file=getsizes',
		data:
		{
			product_id: value,
		},
		dataType: 'json',
		success: function(result){
			if(result.success){
				$('#field_size_id').html(result.html);
				$('#field_size_id').trigger('change');
				$('#field_product_id, #field_size_id').prop('disabled', false);
				$('body').removeClass('loading');
			}
			if(result.message){
				var n = noty({
					text: result.message,
					type: (result.success ? 'success' : 'error')
				});
			}
		},
		error: function(result){
			var n = noty({
				text: 'Something went wrong, maybe the internet connection is a bit choppy',
				type: 'error'
			});
		}
	});
/*
	$('#field_size_id').html('<option>Something</option>');
	$('#field_size_id').trigger('change');
	console.log('wef');
*/
}