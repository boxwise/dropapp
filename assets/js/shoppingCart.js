$(document).ready(function() {
    // =============================
    // NEW: Configuration constants
    // =============================
    const CART_EXPIRATION_HOURS = 2;
    const CART_EXPIRATION_MS = CART_EXPIRATION_HOURS * 60 * 60 * 1000;
    const SESSION_KEY = 'shopping_cart_session_active';
    const SHOPPING_CART_PREFIX = 'shopping_cart_';

    // =============================
    // NEW: Helper functions
    // =============================
    
    // Helper function to get people_id from URL
    function getPeopleIdFromUrl() {
        var search = window.location.search;
        if (!search) return null;
        
        var params = {};
        var pairs = search.substring(1).split('&');
        for (var i = 0; i < pairs.length; i++) {
            var pair = pairs[i].split('=');
            if (pair.length === 2) {
                params[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);
            }
        }
        return params.people_id || null;
    }

    // Get current people_id from either dropdown field or URL
    function getCurrentPeopleId() {
        return $("#field_people_id").val() || getPeopleIdFromUrl();
    }

    // Check if this is a new browser session (new tab)
    function isNewBrowserSession() {
        try {
            if (typeof(Storage) !== "undefined" && window.sessionStorage) {
                var sessionActive = sessionStorage.getItem(SESSION_KEY);
                if (!sessionActive) {
                    sessionStorage.setItem(SESSION_KEY, 'true');
                    return true;
                }
                return false;
            }
            return false;
        } catch (e) {
            console.warn('Session storage not available:', e);
            return false;
        }
    }

    // Initialize people_id from URL if present
    function initializePeopleIdFromUrl() {
        var peopleId = getPeopleIdFromUrl();
        if (peopleId && $("#field_people_id").length) {
            $("#field_people_id").val(peopleId);
        }
    }

    var shoppingCart = (function() {
        // =============================
        // Private methods and properties
        // =============================
        var cart = [];
        
        // Constructor
        function Item(id, name, nameWithoutPrice, count, price) {
          this.id = id;
          this.name = name;
          this.nameWithoutPrice = nameWithoutPrice;
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
        // Public methods and properties
        // =============================
        var obj = {};
        
        // Add to cart
        obj.addItemToCart = function(id, name, price, count) {
          loadCart();
          for(var i = 0; i < cart.length; i++) {
            if(cart[i].id === id) {
                cart[i].count = parseInt(cart[i].count) + parseInt(count);
                saveCart();
                return;
            }
          }
          var nameWithoutPrice = name.substr(0, name.indexOf('('));
          var item = new Item(id, name, nameWithoutPrice, parseInt(count), parseInt(price));
          cart.push(item);
          saveCart();
        }
        
        // Set count from item
        obj.setCountForItem = function(name, count) {
          for(var i = 0; i < cart.length; i++) {
            if (cart[i].name === name) {
              cart[i].count = count;
              break;
            }
          }
          saveCart();
        };
        
        // Remove item from cart
        obj.removeItemFromCart = function(id) {
            for(var i = 0; i < cart.length; i++) {
              if(cart[i].id === id) {
                cart[i].count --;
                if(cart[i].count === 0) {
                  cart.splice(i, 1);
                }
                break;
              }
          }
          saveCart();
        }
      
        // Remove all items from cart
        obj.removeItemFromCartAll = function(id) {
          for(var i = 0; i < cart.length; i++) {
            if(cart[i].id === id) {
              cart.splice(i, 1);
              break;
            }
          }
          saveCart();
        }

        // Update quantity of one product in cart
        obj.updateItemQuantityInCart = function(id, newQuantity) {
            for(var i = 0; i < cart.length; i++) {
                if(cart[i].id === id) {
                    cart[i].count = newQuantity;
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
          for(var i = 0; i < cart.length; i++) {
            totalCount += cart[i].count;
          }
          return totalCount;
        }
      
        // Total cart
        obj.totalCart = function() {
          var totalCart = 0;
          for(var i = 0; i < cart.length; i++) {
            totalCart += cart[i].price * cart[i].count;
          }
          return Number(totalCart.toFixed(2));
        }
      
        // List cart
        obj.listCart = function() {
          var cartCopy = [];
          for(var i = 0; i < cart.length; i++) {
            var item = cart[i];
            var itemCopy = {};
            for(var p in item) {
              itemCopy[p] = item[p];
            }
            itemCopy.total = Number(item.price * item.count).toFixed(2);
            cartCopy.push(itemCopy)
          }
          return cartCopy;
        }
      
        return obj;
    })();

    function renderCart(){
        if (shoppingCart.totalCount()) {
            $('#shopping_cart_outer_div').removeClass('hidden');
        } else {
            $('#shopping_cart_outer_div').addClass('hidden');
        }

        $("#shopping_cart").find("tr:gt(0)").remove();
        var cartItems = shoppingCart.listCart();
        
        cartItems.forEach((item) => {
          let tableRef = document.getElementById("shopping_cart");
          
          let tr = jQuery('<tr/>')
            .appendTo(tableRef);

          jQuery('<td />')
            .text(item.name)
            .appendTo(tr);

          let amountCell = jQuery('<td/>').appendTo(tr);
          jQuery('<input/>', {
            'type': 'number',
            'class': 'form-control valid changeQuantity',
            'data-testid': 'changeQuantity',
            'step': '1',
            'min': '1',
            'productid': item.id,
            'value': item.count,
          }).appendTo(amountCell);

          jQuery('<td />', {
            'data-testid': 'price'
          })
            .text(item.price)
            .appendTo(tr);

          jQuery('<td />', {
            'id': 'totalSum_' + item.id,
            'data-testid': 'totalPrice'
          })
            .text(item.price * item.count)
            .appendTo(tr);

          let deleteCell = jQuery('<td/>')
            .appendTo(tr);
          let deleteCellButton = jQuery('<button/>', {
            'type': 'button',
            'class': 'btn btn-sm deleteFromCart',
            'data-testid': 'deleteFromCart',
            'productid': item.id,
          }).appendTo(deleteCell);
          jQuery('<i/>', {
            'class': 'fa fa-trash-o'
          }).appendTo(deleteCellButton);
        });
        updateCartRelatedElements();
    }

    function updateCartRelatedElements(){
        var dropcredit = $("#dropcredit").data("dropCredit") || 0;

        // Calculate cart value and remaining credit
        $("#cartvalue_aside")[0].innerText = shoppingCart.totalCart();
        $("#creditvalue_aside")[0].innerText = dropcredit - shoppingCart.totalCart();
        if (shoppingCart.totalCart()) {
            $('#cart_value').removeClass('hidden');
        } else {
            $('#cart_value').addClass('hidden');
        }

        // Check if beneficiary has enough tokens
        var enough_tokens = dropcredit >= shoppingCart.totalCart();
        if (enough_tokens) {
            $(".aside-form").removeClass("not_enough_coins");
        } else {
            $(".aside-form").addClass("not_enough_coins");
        }

        //not the same as totalCart worth zero! some items can be free of charge
        var isCartEmpty = shoppingCart.totalCount() == 0;
        $('#submitShoppingCart').prop("disabled", isCartEmpty || !enough_tokens);
    }

    function updatePriceInRow(){
        var cartItems = shoppingCart.listCart();
        cartItems.forEach((item) => {
            var id = "totalSum_" + item.id;
            var totalPriceCell = $("#"+id)[0];
            if (totalPriceCell) {
                totalPriceCell.innerText = item.price * item.count;
            }
        });
        updateCartRelatedElements();
    }

    $("#field_product_id").on('change', function(e) {
        var product_id = $("#field_product_id").val();
        var people_id = getCurrentPeopleId(); // UPDATED: Use helper function
        $("#add-to-cart-button").prop("disabled", !(people_id && product_id));
    });

    $("#field_people_id").on('change', function(e) {
        var people_id = $("#field_people_id").val();
        if (people_id === ""){
            $("[id=ajax-content]").hide();
        } else {
            $("[id=ajax-content]").show();
        }
    });

    $("#add-to-cart-button").click(function(e) {
        e.preventDefault();

        var count = $(this).closest("form").find("input[name='count']").val();
        var productId = $("#field_product_id").val();
        var productName = $("#select2-chosen-2")[0].innerText;
        var price = parseInt($("#field_product_id").children(":selected").data("price"));
        shoppingCart.addItemToCart(productId, productName, parseInt(price), parseInt(count));
        renderCart();

        //reset form
        $('#field_product_id').val(null).trigger('change');
        $("#field_count").val(1);
    });

    $(document).on("click",'.deleteFromCart', function(e){
        var productId = e.target.getAttribute('productId');
        if (!productId) {
            // event triggered by the trash icon rather than button itself
            var productId = e.target.parentElement.getAttribute('productId');
        }
        shoppingCart.removeItemFromCartAll(productId);
        renderCart();
    });

    $(document).on("change",'.changeQuantity', function(e){
        e.preventDefault();
        var productId = event.target.getAttribute('productId');
        var newQuantity = event.target.value;
        shoppingCart.updateItemQuantityInCart(productId, newQuantity);
        updatePriceInRow();
    });

    $(document).on("keydown keyup keypress",'.changeQuantity', function(e){
        if (e.which == 13) e.preventDefault();
    });

    $(document).on("click",'#submitShoppingCart', function(e){
        e.preventDefault();
        $("#submitShoppingCart").prop("disabled", true);
        var cart = shoppingCart.listCart();
        var people_id = getCurrentPeopleId();
        $.ajax({
            type: "post",
            url: "ajax.php?file=check_out",
            data: {
                cart: JSON.stringify(cart),
                people_id: people_id
            },
            dataType: "json",
            success: function(result) {
                if (result.success) {
                  shoppingCart.clearCart();
                  renderCart();
                    if (result.message) {
                        noty({
                            text: result.message,
                            type: result.success ? "success" : "error",
                            closeWith: ['click'],
                            timeout: 5000,
                            callback: {
                                afterClose: function() {
                                    execReload(result.redirect);
                                }
                            }
                        });
                    }          
                } else {
                  $("#submitShoppingCart").prop("disabled", false);
                }
            },
            error: function(result) {
                $("#submitShoppingCart").prop("disabled", false);
                var n = noty({
                    text: "We cannot connect to the Boxtribute server.<br> Do you have internet?",
                    type: "error"
                });
            }
        });
    });    

    // Initialize people_id from URL on page load
    setTimeout(function() {
        initializePeopleIdFromUrl();
        renderCart();
    }, 100);
});