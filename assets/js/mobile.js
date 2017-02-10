$(function(){
	$('.selectsearch').select2();
	
	$('form').validate({
	  submitHandler: function(form) {
	    form.submit();
	  }
	 });
})

function getSizes(size){
	$('#field_product_id, #field_size_id').prop('disabled', true);
	value = $('#field_product_id').val();
	$('body').addClass('loading');
	$.ajax({
		type: 'post',
		url: 'ajax.php?file=getsizes&size='+size,
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
/*
			if(result.message){
				var n = noty({
					text: result.message,
					type: (result.success ? 'success' : 'error')
				});
			}
*/
		},
		error: function(result){
/*
			var n = noty({
				text: 'Something went wrong, maybe the internet connection is a bit choppy',
				type: 'error'
			});
*/
		}
	});
}