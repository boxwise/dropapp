$(function(){
	$('.selectsearch').select2();
	
	$('form').validate({
	  submitHandler: function(form) {
		$(form).find(':input[type=submit]').prop('disabled', true);
	    form.submit();
	  }
	 });
	$('#campselect select').on('change', function(){
		window.location = $(this).val();
		return false;
	})

	$('.toggle-do').click(function(e){
		$(this).parent().next().toggleClass('hide');
		e.preventDefault();
	})
})

function updateSizes(size){
	var sizegroup = $('#field_product_id :selected').data('sizegroup');
	$('#field_size_id').html($('.all-sizes .sizegroup-'+sizegroup).clone());
	if($('.all-sizes .sizegroup-'+sizegroup).length > 1){
		$('#field_size_id').prepend('<option selected value="">Select a size</option>');
	}
}