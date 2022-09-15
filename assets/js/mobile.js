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

function disableBoxEdit(){
	$("#field_product_id, #field_size_id, #tags, #items, #comments").prop("disabled", true);
	$("#field_product_id, #field_size_id, #tags, #items, #comments").prop("readonly", true);
}

function enableBoxEdit(){
	$("#field_product_id, #field_size_id, #tags, #items, #comments").prop("disabled", false);
	$("#field_product_id, #field_size_id, #tags, #items, #comments").prop("readonly", false);
}

function getNewBoxState() {
    let locationId = $("#location_id").val();

    let lostEl = document.getElementById("lost");
    let scrapEl = document.getElementById("scrap");
    $("#scrap, #lost").attr('disabled',false);

    $.ajax({
        type: "post",
        url: "/ajax.php?file=checkboxstate",
        data: {
            id: locationId,
        },
        dataType: "json",
        success: function (result) {

            if (typeof result.message === 'object') {
                $("#newstate").hide();
                $("#newstate").html(result.message.box_state);
                $("#newstate").fadeIn(2000);
                
                if(parseInt(result.message.box_state_id) === 2){
                    lostEl.checked = true;
                    scrapEl.checked = false;

                    $("#scrap, #lost").attr('disabled',true);
					disableBoxEdit()

                } else if(parseInt(result.message.box_state_id) === 6){
                    lostEl.checked = false;
                    scrapEl.checked = true;

                    $("#scrap, #lost").attr('disabled',true);
					disableBoxEdit()

                } else {
                    lostEl.checked = false;
                    scrapEl.checked = false;
					enableBoxEdit()
                }

            } else {
                $("#newstate").hide();
            }
            return true;
        },
        error: function (err) {
			return false;
		},
    });

    return false;
}


function setBoxState(state){

    let lostEl = document.getElementById("lost");
    let scrapEl = document.getElementById("scrap");
  
    switch(state){
        case "lost":
            if (!lostEl.checked) {
                console.log("lost: " + lostEl.checked);
                lostEl.checked = false;
                $("#newstate").hide();
                $("#newstate").html('');
                $("#newstate").fadeIn(2000);
              } else {
                console.log("lost: " + lostEl.checked);
                lostEl.checked = true;
                scrapEl.checked = false;
                $("#newstate").hide();
                $("#newstate").html(' -> <span style="color:blue">Lost</span>');
                $("#newstate").fadeIn(2000);
              }
            break;
        case "scrap":
            if (!scrapEl.checked) {
                console.log("scrap: " + scrapEl.checked);
                scrapEl.checked = false;
                $("#newstate").hide();
                $("#newstate").html('');
                $("#newstate").fadeIn(2000);
              } else {
                console.log("scrap: " + scrapEl.checked);
                scrapEl.checked = true;
                lostEl.checked = false;
                $("#newstate").hide();
                $("#newstate").html(' -> <span style="color:blue">Scrap</span>');
                $("#newstate").fadeIn(2000);
              }
            
            break;
    }

    if(scrapEl.checked || lostEl.checked){
        disableBoxEdit();
    } else {
        enableBoxEdit();
    }
}