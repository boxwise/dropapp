const MIN_VALID_DATE = new Date("January 1, 1900 00:00:00");

$(document).ready(function () {
    // Notification throught the redirect command
    var parts = window.location.search.substr(1).split("&");
    var $_GET = {};
    for (var i = 0; i < parts.length; i++) {
        var temp = parts[i].split("=");
        $_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
    }
    if ($_GET["warning"]) {
        var n = noty({
            text: $_GET["message"],
            type: "error",
        });
    } else if ($_GET["message"]) {
        var n = noty({
            text: $_GET["message"],
            type: "success",
        });
    }

    // Show users menu only if the user can see a usergroup
    if ($(".menu_cms_users").length) {
        $.ajax({
            type: "post",
            url: "ajax.php?file=checkusersmenu",
            dataType: "json",
            success: function (result) {
                AjaxCheckSuccess(result);

                if (!result.showusermenu) {
                    $(".menu_cms_users").hide();
                }
            },
            error: AjaxError,
        });
    }
});

$(function () {
    $(".bar").click(function (e) {
        $("body").toggleClass("different-view");
    });

    $(".laundry-showhide").click(function (e) {
        i = $(this).data("day");
        $("#laundry-table-" + i).toggleClass("hidden");
        $(".icon-close-" + i).toggleClass("hidden");
        $(".icon-open-" + i).toggleClass("hidden");
    });

    // Signature handling
    // http://keith-wood.name/signature.html
    old = $("#signaturefield").val();
    // Set old for beneficiaries without a signature
    if (old === "") old = { lines: [] };
    // disable signature field if old signature exis

    $("#sig").signature({
        change: function (event, ui) {
            //change hidden approvalsigned field
            $("#field_approvalsigned").prop(
                "checked",
                !$("#sig").signature("isEmpty")
            );
            // field for signature date
            if (
                !$("#sig").signature("isEmpty") &&
                $("#field_date_of_signature").val() == "0000-00-00 00:00:00"
            ) {
                $("#field_date_of_signature").val(
                    new Date().toISOString().slice(0, 19).replace("T", " ")
                );
            }
        },
    });

    $("#sig").signature({
        color: "#0000ff",
        guideline: true,
        syncField: "#signaturefield",
        syncFormat: "JSON",
    });

    $("#sig").signature("draw", old);

    $("#sig").signature({ disabled: !$("#sig").signature("isEmpty") });

    $("#clear").click(function () {
        $("#sig").signature("enable");
        $("#sig").signature("clear");
        $("#field_date_of_signature").val("0000-00-00 00:00:00");
        return false;
    });

    if ($("#field_valid_firstday_datepicker").length) {
        $("#field_valid_firstday_datepicker")
            .data("DateTimePicker")
            .useCurrent(false);
        let today = new Date(Date.now());
        today.setHours(0, 0, 0, 0);
        $("#field_valid_firstday_datepicker")
            .data("DateTimePicker")
            .minDate(today);
    }
    if ($("#field_valid_lastday_datepicker").length) {
        $("#field_valid_lastday_datepicker")
            .data("DateTimePicker")
            .useCurrent(false);
        let today = new Date(Date.now());
        today.setHours(0, 0, 0, 0);
        $("#field_valid_lastday_datepicker")
            .data("DateTimePicker")
            .minDate(today);
    }

    $("#field_valid_firstday_datepicker").on("dp.change", function (e) {
        var today = new Date(Date.now());
        today.setHours(0, 0, 0, 0);
        field_date = $("#field_valid_lastday_datepicker")
            .data("DateTimePicker")
            .date();
        $("#field_valid_lastday_datepicker")
            .data("DateTimePicker")
            .minDate(today > e.date._d ? today : e.date._d);
        if (field_date) {
            if (field_date < e.date._d) {
                $("#field_valid_lastday_datepicker")
                    .data("DateTimePicker")
                    .clear();
            }
        }
    });
    $("#field_valid_lastday_datepicker").on("dp.change", function (e) {
        $("#field_valid_firstday_datepicker").data("DateTimePicker");
    });
});

//limit date of beneficiary birth to max today
$(document).ready(function () {
    if ($("#field_date_of_birth_datepicker").length) {
        var DateValue = $("#field_date_of_birth").val();
        var date = new Date();
        $("#field_date_of_birth_datepicker")
            .data("DateTimePicker")
            .maxDate(date.toLocaleDateString("en-GB"))
            .viewMode("years");
        $("#field_date_of_birth").val(DateValue);
    }
});

// The function actually applying the offset
function offsetAnchor() {
    if (location.hash.length !== 0) {
        window.scrollTo(window.scrollX, window.scrollY - 100);
    }
}

function AjaxCheckSuccess(result) {
    if (result.message) {
        var n = noty({
            text: result.message,
            type: result.success ? "success" : "error",
        });
    }
    if (result.redirect) {
        if (result.message) {
            setTimeout(function () {
                execReload(result.redirect);
            }, 1500);
        } else {
            execReload(result.redirect);
        }
    }

    if (result.action) {
        eval(result.action);
    }
    if (typeof result.success == "undefined") {
        var n = noty({
            text: "Something went wrong - please inform your coordinator.",
            type: "error",
        });
    }
}

function AjaxError(result) {
    if ([0, 404, 408, 429].includes(result.status)) {
        var n = noty({
            text:
                "Cannot connect to Boxtribute - please check your Internet connection.",
            type: "error",
        });
    } else {
        var n = noty({
            text: "Something went wrong - please inform your coordinator.",
            type: "error",
        });
    }
}
// Captures click events of all <a> elements with href starting with #
$(document).on("click", 'a[href^="#"]', function (event) {
    // Click events are captured before hashchanges. Timeout
    // causes offsetAnchor to be called after the page jump.
    window.setTimeout(function () {
        offsetAnchor();
    }, 0);
});
// Set the offset when entering page with hash present in the url
window.setTimeout(offsetAnchor, 0);

function cms_form_valutaCO(field) {
    value = $("#field_" + field).val();
    if (value.substr(0, 2) == "� ") value = value.replace(/\./g, "");
    if (value.substr(0, 1) == "�") value = value.substr(1);
    if (value.substr(0, 1) == " ") value = value.substr(1);
    value = parseFloat(value.replace(/,/g, ".")).toFixed(2).replace(/\./g, ",");
    if (value == "NaN") value = "";
    if (value == "") {
        $("#field_" + field).val();
    } else {
        $("#field_" + field).val("� " + PointPerThousand(value));
    }
}

$(document).ready(function () {
    if ($("#field_laundryblock").length) {
        if (!$("#field_laundryblock").is(":checked"))
            $("#div_laundrycomment").hide();
    }
});
$("#field_laundryblock").click(function () {
    if ($(this).is(":checked")) {
        $("#div_laundrycomment").show();
    } else {
        $("#field_laundrycomment").val("");
        $("#div_laundrycomment").hide();
    }
});

function toggleLunch() {
    $("#div_lunchtime, #div_lunchduration").toggleClass("hidden");
}

function toggleSomething() {
    console.log("something");
}

function toggleDiscountFields() {
    var selectedVal = $("#field_discount_type").find(":selected").val();

    $("#div_discount_amount").addClass("hidden");
    $("#div_discount_perc").addClass("hidden");
    $("#div_discount_" + selectedVal).removeClass("hidden");
}

if ($("#field_people_id").val() != undefined) {
    eval($("#field_people_id").attr("onchange"));
}

function capitalize(field) {
    value = $("#field_" + field).val();
    $("#field_" + field).val(value.toUpperCase());
}

function updateLaundry(field, offset) {
    value = $("#field_" + field).val();
    timeslot = $("#field_timeslot").val();
    if (value) {
        $("#form-submit").prop("disabled", true);
        $("#field_" + field).prop("disabled", true);
        $("body").addClass("loading");
        $.ajax({
            type: "post",
            url: "ajax.php?file=laundry",
            data: {
                people_id: value,
                offset: offset,
                timeslot: timeslot,
            },
            dataType: "json",
            success: function (result) {
                var url = window.location;
                var action = $("body").data("action");
                if (result.success) {
                    $("#ajax-content").html(result.htmlcontent);
                    $("#field_" + field).prop("disabled", false);
                    $("body").removeClass("loading");
                }
                AjaxCheckSuccess(result);
            },
            error: AjaxError,
        });
    }
}

function selectFamily(field, reload, target) {
    value = $("#field_" + field).val();
    product = $("#field_product_id").val();

    $("#add-to-cart-button").prop("disabled", !(product && value));
    var queryDict = {};
    location.search
        .substring(1)
        .split("&")
        .forEach(function (item) {
            queryDict[item.split("=")[0]] = item.split("=")[1];
        });

    if (value) {
        if (queryDict["people_id"] != value && reload)
            window.location = "?action=" + target + "&people_id=" + value;

        if (value != $("#div_purch").data("listid")) {
            $("#div_purch").hide();
        }
        $("#form-submit").prop("disabled", true);
        $("#field_" + field).prop("disabled", true);
        $("body").addClass("loading");
        $.ajax({
            type: "post",
            url: "ajax.php?file=" + target,
            data: {
                people_id: value,
            },
            dataType: "json",
            success: function (result) {
                var url = window.location;
                var action = $("body").data("action");
                window.history.pushState(
                    action,
                    "Check Out",
                    url.toString().split("?")[0] +
                        "?action=" +
                        action +
                        "&people_id=" +
                        value
                );
                if (result.success) {
                    $("#ajax-shopping-cart").html(result.htmlshoppingcart);
                    $("#ajax-last-purchases").html(result.htmllastpurchases);
                    initiateList();
                    $("#ajax-aside").html(result.htmlaside);
                    $(".not_enough_coins").removeClass("not_enough_coins");
                    $("#field_" + field).prop("disabled", false);
                    $("body").removeClass("loading");
                    
                    // Load cart for the selected person
                    if (typeof window.shoppingCart !== 'undefined' && window.shoppingCart.loadCart) {
                        window.shoppingCart.loadCart(value);
                        
                        // Render cart after DOM is ready
                        setTimeout(function() {
                            if (typeof window.renderCart === 'function') {
                                window.renderCart();
                            }
                        }, 150);
                    }
                }
                AjaxCheckSuccess(result);
            },
            error: AjaxError,
        });
    } else {
        $("#dropcredit").data({ dropCredit: 0 });
        $("#people_id_selected").addClass("hidden");
    }
}

function selectFamilyhead(field, targetfield) {
    value = $("#field_" + field).val();
    if (value === "") {
        $("#field_" + targetfield).val("");
    } else {
        $("#field_" + targetfield).val(
            $("#field_" + field + " option[value=" + value + "]").data("value2")
        );
    }
}

function checkTags(id) {
    let selectedType = $("#field_type").val();
    $.ajax({
        type: "post",
        url: "/ajax.php?file=checktag",
        data: {
            type: selectedType,
            id: id,
        },
        dataType: "json",
        success: function (result) {
            AjaxCheckSuccess(result);
        },
        error: AjaxError,
    });
}

function getNewBoxState() {
    let locationId = $("#field_location_id").val();

    let lostEl = document.getElementById("field_lost");
    let scrapEl = document.getElementById("field_scrap");
    $("#field_scrap, #field_lost").attr('disabled',false);

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
                $("#newstate").fadeIn(500)
                
                if(parseInt(result.message.box_state_id) === 2){
                    lostEl.checked = true;
                    scrapEl.checked = false;

                    $("#field_scrap, #field_lost").attr('disabled',true);

                } else if(parseInt(result.message.box_state_id) === 6){
                    lostEl.checked = false;
                    scrapEl.checked = true;

                    $("#field_scrap, #field_lost").attr('disabled',true);

                } else {
                    lostEl.checked = false;
                    scrapEl.checked = false;
                    $("#field_location_id,#field_product_id, #field_size_id, #field_tags, #field_items, #field_comments").prop("disabled", false);
                    $("#field_location_id,#field_product_id, #field_size_id, #field_tags, #field_items, #field_comments").prop("readonly", false);
                }

            } else {
                $("#newstate").hide();
            }
            return true;
        },
        error: AjaxError,
    });

    return false;
}


function disableBoxEdit(){
    $("#field_location_id,#field_product_id, #field_size_id, #field_tags, #field_items, #field_comments").attr("disabled", true);
    $("#field_location_id,#field_product_id, #field_size_id, #field_tags, #field_items, #field_comments").attr("readonly", true);
    $("input[name='__items'], input[name='__comments']").val('text  readonly');
}

function enableBoxEdit() {
    $("#field_location_id,#field_product_id, #field_size_id, #field_tags, #field_items, #field_comments").attr("disabled", false);
    $("#field_product_id, #field_size_id, #field_tags, #field_items, #field_comments").attr("readonly", false);
    $("input[name='__items'], input[name='__comments']").val('text  ');
}

function setBoxState(state){

    let lostEl = document.getElementById("field_lost");
    let scrapEl = document.getElementById("field_scrap");
  
    switch(state){
        case "lost":
            if (!lostEl.checked) {
                lostEl.checked = false;
                $("#newstate").hide();
                $("#newstate").html('');
                $("#newstate").fadeIn(500);
              } else {
                lostEl.checked = true;
                scrapEl.checked = false;
                $("#newstate").hide();
                $("#newstate").html(' &rarr; <span style="color:blue">Lost</span>');
                $("#newstate").fadeIn(500);
              }
            break;
        case "scrap":
            if (!scrapEl.checked) {
                scrapEl.checked = false;
                $("#newstate").hide();
                $("#newstate").html('');
                $("#newstate").fadeIn(500);
              } else {
                scrapEl.checked = true;
                lostEl.checked = false;
                $("#newstate").hide();
                $("#newstate").html(' &rarr; <span style="color:blue">Scrap</span>');
                $("#newstate").fadeIn(500);
              }
            
            break;
    }

    if(scrapEl.checked || lostEl.checked){
        disableBoxEdit();
    } else {
        enableBoxEdit();
    }
}


function correctDrops(first, second) {
    $("#row-" + first.id + " .list-column-drops .td-content").text(first.value);
    $("#row-" + second.id + " .list-column-drops .td-content").text(
        second.value
    );
}

function getSizes() {
    $("#field_product_id, #field_size_id").prop("disabled", true);
    value = $("#field_product_id").val();
    $("body").addClass("loading");
    $.ajax({
        type: "post",
        url: "ajax.php?file=getsizes",
        data: {
            product_id: value,
        },
        dataType: "json",
        success: function (result) {
            if (result.success) {
                $("#field_size_id").html(result.html);
                $("#field_size_id").trigger("change");
                $("#field_product_id, #field_size_id").prop("disabled", false);
                $("body").removeClass("loading");
            }
            AjaxCheckSuccess(result);
        },
        error: AjaxError,
    });
    /*
	$('#field_size_id').html('<option>Something</option>');
	$('#field_size_id').trigger('change');
	console.log('wef');
*/
}

/*
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
*/

$(".check-minmax").on("input", function (ev) {
    var min = 0;
    var max = Number($(this).attr("placeholder"));
    var that = $(this);
    if (that.val() < min || that.val() > max) {
        $("#form-submit").prop("disabled", true);
        that.addClass("error");
    }
    setTimeout(
        function (that, min, max) {
            if (that.val() < min) that.val(min).removeClass("error");
            if (that.val() > max) that.val(max).removeClass("error");
            $("#form-submit").prop("disabled", false);
        },
        2000,
        that,
        min,
        max
    );
});

// Delete button in cms_profile
$(".delete-user").on("click", function (e) {
    var el = $(this);
    e.preventDefault();

    var options = $.extend(
        {
            container: "body",
            singleton: true,
            popout: true,
            trigger: "manual",
            onConfirm: function (e, element) {
                element.data("confirmed", true).trigger("click");
                e.preventDefault();
            },
        },
        el.data()
    );
    el.confirmation(options);

    if (el.is(".confirm") && !el.data("confirmed")) {
        el.confirmation("show");
    } else if (el.data("confirmed")) {
        el.data("confirmed", false);
        $.ajax({
            type: "post",
            url: "ajax.php?file=deleteprofile",
            dataType: "json",
            success: function (result) {
                AjaxCheckSuccess(result);
            },
            error: AjaxError,
        });
    }
});

//Checkboxes in Bases menu
function toggleShop() {
    $("#tabid_market").toggleClass("hidden");
}
function toggleFood() {
    $("#tabid_food").toggleClass("hidden");
}
function toggleBikes() {
    $("#tabid_bicycle").toggleClass("hidden");
}

// Function to handle conditional toggling based on checkbox field
function conditionalToggle(checkboxName, textFieldName) {
    const checkbox = document.querySelector(`input[name="${checkboxName}"]`);
    const targetField = document.querySelector(`input[name="${textFieldName}"]`);

    if (checkbox && targetField) {
        document.addEventListener("change", function (e) {
            if (e.target === checkbox) {
                targetField.disabled = !checkbox.checked;
                if (checkbox.checked) {
                    targetField.focus(); 
                    targetField.setAttribute("aria-required", "true");
                    targetField.setAttribute("required", "required");
                } else {
                    targetField.value = "";
                    targetField.removeAttribute("aria-required");
                    targetField.removeAttribute("required");
                    targetField.classList.remove("error");
                    if (targetField.hasAttribute("data-hasqtip")) {
                        const qtipId = targetField.getAttribute("data-hasqtip");
                        const tooltipElement = document.getElementById(`qtip-${qtipId}`);
                        if (tooltipElement) {
                            tooltipElement.remove();
                        }
                        targetField.removeAttribute("data-hasqtip");
                        targetField.removeAttribute("aria-describedby");
                        targetField.setAttribute("aria-invalid", "false");
                    }
                }
            }
        });
    }
}
