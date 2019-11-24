$(function(){
	$('.selectsearch').select2();
	
	$('form').validate({
	  submitHandler: function(form) {
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

function saveBoxNotification(boxId, boxNumber, itemsCount, product, boxLocation, isWarning){
	var notificationText = "New box with box ID <strong class='bigger'>" + boxNumber + "</strong> (write this number on the box label). This box contains  <strong class='bigger'>" + itemsCount + " " + product + "</strong> and is located in  <strong class='bigger'>" + boxLocation + "</strong>. <a href='?boxid=" + boxId + "'>Edit this box.</a>";
	showNotyNotification(notificationText, isWarning);
}

function editBoxNotification(boxId, boxNumber, itemsCount, product, boxLocation, isWarning){
	var notificationText = "Box <strong class='bigger'>" + boxNumber + "</strong> modified with <strong class='bigger'>" + product + " (" + itemsCount + "x)</strong> in <strong class='bigger'>" + boxLocation + "</strong>. <a href=\"?boxid=" + boxId + "\">Go back to this box.</a>";
	showNotyNotification(notificationText, isWarning);
}

function saveAmountNotification(boxId, boxNumber, itemsCount, product,isWarning){
	var notificationText = "Box <strong class='bigger'>" + boxNumber + "</strong> contains now <strong class='bigger'>" + itemsCount + "x " + product + "</strong>. <a href=\"?boxid=" + boxId + "\">Go back to this box.</a>";
	showNotyNotification(notificationText, isWarning);
}

function showNotyNotification(notificationText, isWarning){
	setTimeout(function(){
		noty({
			text: notificationText,
			type: isWarning ? 'error': 'success',
			closeWith: ['click'],
			timeout: 10000
		});
	}, 100);
}