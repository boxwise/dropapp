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

function toggleLunch() {
	$('#div_lunchtime, #div_lunchduration').toggleClass('hidden');
}

function toggleSomething(){
	console.log('something');
}

function toggleDiscountFields() {

	var selectedVal = $('#field_discount_type').find(":selected").val();

	$('#div_discount_amount').addClass('hidden');
	$('#div_discount_perc').addClass('hidden');
	$('#div_discount_'+selectedVal).removeClass('hidden');
}

function toggleLibraryComment() {
	value = $('#field_people_id').find(":selected").val();
	if(value==-1) $('#div_comment').show();
	if(value>0) $('#div_comment').hide();
}

if($('#field_people_id').val() != undefined){
	eval(($('#field_people_id').attr('onchange')));
}

function capitalize(field) {
	value = $('#field_'+field).val();
	$('#field_'+field).val(value.toUpperCase());
}

function laundry(i) {
	value = $(i).attr('name');
	alert(value);
}
function updateLaundry(field) {
	value = $('#field_'+field).val();
	timeslot = $('#field_timeslot').val();
	if(value) {
		$('#form-submit').prop('disabled', true);
		$('#field_'+field).prop('disabled', true);
		$('body').addClass('loading');
		$.ajax({
			type: 'post',
			url: 'include/laundry_ajax.php',
			data:
			{
				people_id: value,
				timeslot: timeslot,
			},
			dataType: 'json',
			success: function(result){
				var url = window.location;
				var action = $('body').data('action');
				if(result.success){
					$('#ajax-content').html(result.htmlcontent);
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
	}
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
			url: 'include/check_out.php',
			data:
			{
				people_id: value,
			},
			dataType: 'json',
			success: function(result){
				var url = window.location;
				var action = $('body').data('action');
				window.history.pushState(action, "Check Out", url.toString().split("?")[0] + "?action="+action+"&people_id="+value);
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

function selectFood(field_array, dist_id_fieldval){
	var val_array = field_array.map(function(field) {return $('#field_'+field).val();});
	$('#form-submit').prop('disabled', true);
	field_array.map(function(field) {return $('#field_'+field).prop('disabled', true);});
	$('body').addClass('loading');
	$.ajax({
		type: 'post',
		url: 'include/food_checkout_edit.php',
		data:
		{
			foods: val_array,
			dist_id: dist_id_fieldval
		},
		dataType: 'json',
		success: function(result){
			if(result.success){
				$('#ajax-content').html(result.htmlcontent);												
				$('#form-submit').prop('disabled', false);
				field_array.map(function(field) {return $('#field_'+field).prop('disabled', false);});
				$('body').removeClass('loading');
			}
			if(result.message){
				var n = noty({
					text: result.message,
					type: (result.success ? 'success' : 'error')
				});
			}
		},
		error: function(xhr, textStatus, error){
			console.log(xhr.statusText);
      			console.log(textStatus);
      			console.log(error);
			var n = noty({
				text: 'Something went wrong, maybe the internet connection is a bit choppy',
				type: 'error'
			});
		}
	});
}

/*

$('.checkConnectionOnSubmit').on('click', function(ev){
	ev.preventDefault();
	$.ajax({
		type: 'post',
		url: 'ajax.php?file=checkconnection',
		success: function(){
			$('<input />').attr('type', 'hidden').attr('name', $(this).attr('name')).attr('value', $(this).attr('value')).appendTo('#cms_form');
			$('#cms_form').submit();
		},
		error: function(xhr, textStatus, error){
			console.log(xhr.statusText);
      			console.log(textStatus);
      			console.log(error);
			var n = noty({
				text: 'The connection to the server is lost.',
				type: 'error'
			});
		}
	});
});

*/

$('.check-minmax').on('input', function(ev){
	var min = 0;
	var max = Number($(this).attr('placeholder'));
	var that = $(this);
	if(that.val() < min || that.val() > max) {
		$('.checkConnectionOnSubmit').prop('disabled',true);
		that.addClass("error");
	}
	setTimeout(function(that, min, max) {
		if(that.val() < min) that.val(min).removeClass("error");
		if(that.val() > max) that.val(max).removeClass("error");
				$('#form-submit').prop('disabled', false);
		$('.checkConnectionOnSubmit').prop('disabled',false);
	}, 2000, that, min, max);
});
