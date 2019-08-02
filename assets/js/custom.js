const MIN_VALID_DATE = new Date('January 1, 1900 00:00:00');

$(document).ready(function() {
    var shoppingCart = (function() {
        // =============================
        // Private methods and propeties
        // =============================
        cart = [];
        
        // Constructor
        function Item(id, name, count, price) {
          this.id = id;
          this.name = name;
          this.price = price;
          this.count = count;
        }
        
        // Save cart
        function saveCart() {
            sessionStorage.setItem('shoppingCart', JSON.stringify(cart));
        }
        
          // Load cart
        function loadCart() {
          cart = JSON.parse(sessionStorage.getItem('shoppingCart'));
        }
        if (sessionStorage.getItem("shoppingCart") != null) {
          loadCart();
        }
        

        // =============================
        // Public methods and propeties
        // =============================
        var obj = {};
        
        // Add to cart
        obj.addItemToCart = function(id, name, price, count) {
          loadCart();
          for(var item in cart) {
            if(cart[item].id === id) {
                cart[item].count = parseInt(cart[item].count) + parseInt(count);
                saveCart();
                return;
            }
          }
          var item = new Item(id, name, parseInt(count), parseInt(price));
          cart.push(item);
          saveCart();
        }
        // Set count from item
        obj.setCountForItem = function(name, count) {
          for(var i in cart) {
            if (cart[i].name === name) {
              cart[i].count = count;
              break;
            }
          }
        };
        // Remove item from cart
        obj.removeItemFromCart = function(id) {
            for(var item in cart) {
              if(cart[item].id === id) {
                cart[item].count --;
                if(cart[item].count === 0) {
                  cart.splice(item, 1);
                }
                break;
              }
          }
          saveCart();
        }
      
        // Remove all items from cart
        obj.removeItemFromCartAll = function(id) {
          for(var item in cart) {
            if(cart[item].id === id) {
              cart.splice(item, 1);
              break;
            }
          }
          saveCart();
        }

        // Update quantity of one product in cart
        obj.updateItemQuantityInCart = function(id, newQuantity) {
            for(var item in cart) {
                if(cart[item].id === id) {
                    cart[item].count = newQuantity;
                }
            }
            saveCart();
          }
      
        // Clear cart
        obj.clearCart = function() {
          cart = [];
          saveCart();
        }
      
        // Count cart 
        obj.totalCount = function() {
          var totalCount = 0;
          for(var item in cart) {
            totalCount += cart[item].count;
          }
          return totalCount;
        }
      
        // Total cart
        obj.totalCart = function() {
          var totalCart = 0;
          for(var item in cart) {
            totalCart += cart[item].price * cart[item].count;
          }
          return Number(totalCart.toFixed(2));
        }
      
        // List cart
        obj.listCart = function() {
          var cartCopy = [];
          for(i in cart) {
            item = cart[i];
            itemCopy = {};
            for(p in item) {
              itemCopy[p] = item[p];
      
            }
            itemCopy.total = Number(item.price * item.count).toFixed(2);
            cartCopy.push(itemCopy)
          }
          return cartCopy;
        }
      
        // cart : Array
        // Item : Object/Class
        // addItemToCart : Function
        // removeItemFromCart : Function
        // removeItemFromCartAll : Function
        // clearCart : Function
        // countCart : Function
        // totalCart : Function
        // listCart : Function
        // saveCart : Function
        // loadCart : Function
        return obj;
    })();

    function renderCart(){
        $("#shopping_cart").find("tr:gt(0)").remove();
        cart.forEach((item) => {
            $('#shopping_cart')
              .append("<tr class='shoppingCartRow'><td>"
                  + item.name +'</td><td>'
                  + "<input type='number' step='1' min='1' productId='"+item.id+"' class='changeQuantity' value='"+ item.count +"'></input></td><td>"
                  + item.price +"</td><td id='totalSum_" + item.id +"'>"
                  + item.count * item.price +'</td><td>'
                  +"<button type='button' class='btn btn-sm btn-danger deleteFromCart' productId='"+item.id+"')>Delete</button></td></tr>");
        });
        $('#cartWorth')[0].innerText = shoppingCart.totalCart();
    }

    function updatePriceInRow(){
        cart.forEach((item) => {
            var id = "totalSum_" + item.id;
            var totalPriceCell = $("#"+id)[0];
            totalPriceCell.innerText = item.price * item.count;
        });
    }

    $("#add-to-cart-button").click(function(e) {
        e.preventDefault();
        var availableTokens = $("#dropcredit").data("dropCredit");
        var count = $(this).closest("form").find("input[name='count']").val();
        var productId = $("#field_product_id").val();
        var productName = $("#select2-chosen-2")[0].innerText;
        var price = $("#ajax-aside").data("productValue");
        var totalprice = count * price;
        shoppingCart.addItemToCart(productId, productName, parseInt(price), parseInt(count));
        renderCart();

        //reset form
        $("#field_count").val(1);
    });

    $(document).on("click",'.deleteFromCart', function(e){
        var productId = event.target.getAttribute('productId');
        shoppingCart.removeItemFromCartAll(productId);
        renderCart();
    });

    $(document).on("change",'.changeQuantity', function(e){
        e.preventDefault();
        var productId = event.target.getAttribute('productId');
        var newQuantity = event.target.value;
        shoppingCart.updateItemQuantityInCart(productId, newQuantity);
        updatePriceInRow();
        $('#cartWorth')[0].innerText = shoppingCart.totalCart();
    });

    $(document).on("keydown keyup keypress",'.changeQuantity', function(e){
        if (e.which == 13) e.preventDefault();
    });

    $(document).on("click",'#submitShoppingCart', function(e){
        var cart = shoppingCart.listCart();
        var family_id = $("#field_people_id").val();
        $.ajax({
            type: "post",
            url: "ajax.php?file=check_out",
            data: {
                cart: JSON.stringify(cart),
                family_id: family_id
            },
            dataType: "json",
            success: function(result) {
                shoppingCart.clearCart();
                renderCart();
                if (result.success) {

                }
                if (result.success) {
                    $("#ajax-aside").html(result.htmlaside);
                    $(".not_enough_coins").removeClass("not_enough_coins");
                    if ($("#field_product_id").val()) {
                        calcCosts("count");
                    }
                    row.find(".data-field-countupdown-value").html(
                        result.amount
                    );
                    row.find(".data-field-drops2").html(result.drops);
                }
                $("#field_people_id").prop("disabled", false);
                $("body").removeClass("loading");
                if (result.message) {
                    var n = noty({
                        text: result.message,
                        type: result.success ? "success" : "error"
                    });
                }
            },
            error: function(result) {
                var n = noty({
                    text:
                        "Something went wrong, maybe the internet connection is a bit choppyyyy",
                    type: "error"
                });
            }
        });
    });    
});

$(document).ready(function() {
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
            type: "error"
        });
    } else if ($_GET["message"]) {
        var n = noty({
            text: $_GET["message"],
            type: "success"
        });
    }

    // Show users menu only if the user can see a usergroup
    if ($(".menu_cms_users").length) {
        $.ajax({
            type: "post",
            url: "ajax.php?file=checkusersmenu",
            dataType: "json",
            success: function(result) {
                if (result.message) {
                    var n = noty({
                        text: result.message,
                        type: result.success ? "success" : "error"
                    });
                }
                if (!result.showusermenu) {
                    $(".menu_cms_users").hide();
                }
            },
            error: function(result) {
                console.log(result);
                var n = noty({
                    text:
                        "This file cannot be found or what's being returned is not json.",
                    type: "error"
                });
            }
        });
    }
});

$(function() {
    $(".bar").click(function(e) {
        $("body").toggleClass("different-view");
    });

    $(".laundry-showhide").click(function(e) {
        i = $(this).data("day");
        $("#laundry-table-" + i).toggleClass("hidden");
        $(".icon-close-" + i).toggleClass("hidden");
        $(".icon-open-" + i).toggleClass("hidden");
    });

    old = $("#signaturefield").val();
    // Set old for new beneficiaries
    if (old==="") old={"lines":[]};
    
    $("#sig").signature({
        change: function(event, ui) {
            $("#field_approvalsigned").prop(
                "checked",
                !$("#sig").signature("isEmpty")
            );
        }
    });
    $("#sig").signature({
        color: "#0000ff",
        guideline: true,
        syncField: "#signaturefield",
        syncFormat: "JSON"
    });
    $("#sig").signature("draw", old);

    $("#clear").click(function() {
        $("#sig").signature("clear");
        return false;
    });

    //Validate dates in users menu
    $("#field_valid_firstday_datepicker").datetimepicker({
        useCurrent: false
    });
    $("#field_valid_lastday_datepicker").datetimepicker({
        useCurrent: false
    });
    $("#field_valid_firstday_datepicker").on("dp.change", function(e) {
        e.date._d.setDate(e.date._d.getDate()+1);
        $("#field_valid_lastday_datepicker")
            .data("DateTimePicker")
            .minDate(e.date);
    });
    $("#field_valid_lastday_datepicker").on("dp.change", function(e) {
        e.date._d.setDate(e.date._d.getDate()-1);
        $("#field_valid_firstday_datepicker")
            .data("DateTimePicker")
            .maxDate(e.date)
            .minDate(MIN_VALID_DATE);
    });
});

//limit date of beneficiary birth to max today
$(document).ready(function() {
    if ($("#field_date_of_birth_datepicker").length) {
        var DateValue = $("#field_date_of_birth").val();
        var date = new Date();
        $("#field_date_of_birth_datepicker")
            .data("DateTimePicker")
            .maxDate(date.toLocaleDateString('en-GB'))
            .viewMode('years');
        $("#field_date_of_birth").val(DateValue);
    }
});

// The function actually applying the offset
function offsetAnchor() {
    if (location.hash.length !== 0) {
        window.scrollTo(window.scrollX, window.scrollY - 100);
    }
}
// Captures click events of all <a> elements with href starting with #
$(document).on("click", 'a[href^="#"]', function(event) {
    // Click events are captured before hashchanges. Timeout
    // causes offsetAnchor to be called after the page jump.
    window.setTimeout(function() {
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
    value = parseFloat(value.replace(/,/g, "."))
        .toFixed(2)
        .replace(/\./g, ",");
    if (value == "NaN") value = "";
    if (value == "") {
        $("#field_" + field).val();
    } else {
        $("#field_" + field).val("� " + PointPerThousand(value));
    }
}

$(document).ready(function() {
    if ($("#field_laundryblock").length) {
        if (!$("#field_laundryblock").is(":checked"))
            $("#div_laundrycomment").hide();
    }
});
$("#field_laundryblock").click(function() {
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
    var selectedVal = $("#field_discount_type")
        .find(":selected")
        .val();

    $("#div_discount_amount").addClass("hidden");
    $("#div_discount_perc").addClass("hidden");
    $("#div_discount_" + selectedVal).removeClass("hidden");
}

function toggleLibraryComment() {
    value = $("#field_people_id")
        .find(":selected")
        .val();
    if (value == -1) $("#div_comment").show();
    if (value > 0) $("#div_comment").hide();
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
                timeslot: timeslot
            },
            dataType: "json",
            success: function(result) {
                var url = window.location;
                var action = $("body").data("action");
                if (result.success) {
                    $("#ajax-content").html(result.htmlcontent);
                    $("#field_" + field).prop("disabled", false);
                    $("body").removeClass("loading");
                }
                if (result.message) {
                    var n = noty({
                        text: result.message,
                        type: result.success ? "success" : "error"
                    });
                }
            },
            error: function(result) {
                var n = noty({
                    text:
                        "Something went wrong, maybe the internet connection is a bit choppy",
                    type: "error"
                });
            }
        });
    }
}

function selectFamily(field, reload) {
    value = $("#field_" + field).val();

    var queryDict = {};
    location.search
        .substr(1)
        .split("&")
        .forEach(function(item) {
            queryDict[item.split("=")[0]] = item.split("=")[1];
        });

    if (value) {
        if (queryDict["people_id"] != value && reload)
            window.location = "?action=check_out&people_id=" + value;

        if (value != $("#div_purch").data("listid")) {
            $("#div_purch").hide();
        }
        $("#form-submit").prop("disabled", true);
        $("#field_" + field).prop("disabled", true);
        $("body").addClass("loading");
        $.ajax({
            type: "post",
            url: "ajax.php?file=check_out",
            data: {
                people_id: value
            },
            dataType: "json",
            success: function(result) {
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
                    $("#ajax-content").html(result.htmlcontent);
                    initiateList();
                    $("#ajax-aside").html(result.htmlaside);
                    $(".not_enough_coins").removeClass("not_enough_coins");
                    if ($("#field_product_id").val()) {
                        calcCosts("count");
                    }
                    $("#field_" + field).prop("disabled", false);
                    $("body").removeClass("loading");
                }
                if (result.message) {
                    var n = noty({
                        text: result.message,
                        type: result.success ? "success" : "error"
                    });
                }
            },
            error: function(result) {
                var n = noty({
                    text:
                        "Something went wrong, maybe the internet connection is a bit choppy",
                    type: "error"
                });
            }
        });
    } else {
        $("#dropcredit").data({ dropCredit: 0 });
        if ($("#product_id_selected").is(":visible")) {
            calcCosts("count");
        }
        $("#people_id_selected").addClass("hidden");
    }

    //clear cart
    sessionStorage.setItem('shoppingCart', JSON.stringify([]));
}

function selectFamilyhead(field, targetfield) {
    value = $("#field_" + field).val();
    if (value === "") {
        $("#field_"+targetfield).val("");
    } else {
        $("#field_"+targetfield).val($("#field_"+field+ " option[value=" + value + "]").data("value2"));
    }
}

function getProductValue(field) {
    value = $("#field_" + field).val();
    if (value) {
        $("#form-submit").prop("disabled", true);
        $("#field_" + field).prop("disabled", true);
        $("body").addClass("loading");
        $.ajax({
            type: "post",
            url: "ajax.php?file=getproductvalue",
            data: {
                product_id: value
            },
            dataType: "json",
            success: function(result) {
                if (result.success) {
                    $("#ajax-aside").data({ productValue: result.drops });
                    calcCosts("count");
                    $("#field_" + field).prop("disabled", false);
                    $("body").removeClass("loading");
                }
                if (result.message) {
                    var n = noty({
                        text: result.message,
                        type: result.success ? "success" : "error"
                    });
                }
            },
            error: function(result) {
                var n = noty({
                    text:
                        "Something went wrong, maybe the internet connection is a bit choppy",
                    type: "error"
                });
            }
        });
    } else {
        $("#field_" + field).prop("disabled", false);
        $("#productvalue_cart")[0].innerText = "0";
        $("#product_id_selected").addClass("hidden");
    }
}
function calcCosts(field) {
    amount = $("#field_" + field).val() ? $("#field_" + field).val() : 1;
    productvalue = $("#ajax-aside").data("productValue");
    dropcredit = $("#dropcredit").data("dropCredit");
    totalprice = amount * productvalue;
    if (Number.isNaN(totalprice)) {
        totalprice = 0;
    }
    $("#productvalue_aside").text(totalprice);
    $("#productvalue_cart").text(totalprice);
    $("#product_id_selected").removeClass("hidden");

    if (dropcredit >= totalprice) {
        $("#form-submit").prop("disabled", false);
        $("#add-to-cart-button").prop("disabled", false);
        $(".aside-form").removeClass("not_enough_coins");
    } else {
        $("#form-submit").prop("disabled", true);
        $("#add-to-cart-button").prop("disabled", true);
        $(".aside-form").addClass("not_enough_coins");
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
            product_id: value
        },
        dataType: "json",
        success: function(result) {
            if (result.success) {
                $("#field_size_id").html(result.html);
                $("#field_size_id").trigger("change");
                $("#field_product_id, #field_size_id").prop("disabled", false);
                $("body").removeClass("loading");
            }
            if (result.message) {
                var n = noty({
                    text: result.message,
                    type: result.success ? "success" : "error"
                });
            }
        },
        error: function(result) {
            var n = noty({
                text:
                    "Something went wrong, maybe the internet connection is a bit choppy",
                type: "error"
            });
        }
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

$(".check-minmax").on("input", function(ev) {
    var min = 0;
    var max = Number($(this).attr("placeholder"));
    var that = $(this);
    if (that.val() < min || that.val() > max) {
        $("#form-submit").prop("disabled", true);
        that.addClass("error");
    }
    setTimeout(
        function(that, min, max) {
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
$(".delete-user").on("click", function(e) {
    var el = $(this);
    e.preventDefault();

    var options = $.extend(
        {
            container: "body",
            singleton: true,
            popout: true,
            trigger: "manual",
            onConfirm: function(e, element) {
                element.data("confirmed", true).trigger("click");
                e.preventDefault();
            }
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
            data: {
                cms_user_id: el.data("id")
            },
            dataType: "json",
            success: function(result) {
                if (result.message) {
                    var n = noty({
                        text: result.message,
                        type: result.success ? "success" : "error"
                    });
                }
                if (result.redirect) {
                    if (result.message) {
                        setTimeout(function() {
                            execReload(result.redirect);
                        }, 1500);
                    } else {
                        execReload(result.redirect);
                    }
                }
            },
            error: function(result) {
                var n = noty({
                    text:
                        "This file cannot be found or what's being returned is not json.",
                    type: "error"
                });
            }
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
