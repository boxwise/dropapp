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
        if (shoppingCart.totalCount()) {
            $('#shopping_cart_outer_div').removeClass('hidden');
        } else {
            $('#shopping_cart_outer_div').addClass('hidden');
        }

        $("#shopping_cart").find("tr:gt(0)").remove();
        cart.forEach((item) => {
            $('#shopping_cart')
              .append("<tr><td>"
                  + item.name +'</td><td>'
                  + "<input type='number' step='1' min='1' productId='"+item.id+"' class='changeQuantity' value='"+ item.count +"'></input></td><td>"
                  + item.price +"</td><td id='totalSum_" + item.id +"'>"
                  + item.count * item.price +'</td><td>'
                  +"<button type='button' class='btn btn-sm btn-danger deleteFromCart' productId='"+item.id+"')><i class='fa fa-trash-o'></i></button></td></tr>");
        });
        updateCartRelatedElements();
    }

    function updateCartRelatedElements(){
        // Calculate cart value
        $('#cartvalue_aside')[0].innerText = shoppingCart.totalCart();
        if (shoppingCart.totalCart()) {
            $('#cart_value').removeClass('hidden');
        } else {
            $('#cart_value').addClass('hidden');
        }

        // Check if beneficiary has enough tokens
        dropcredit = $("#dropcredit").data("dropCredit");
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
        cart.forEach((item) => {
            var id = "totalSum_" + item.id;
            var totalPriceCell = $("#"+id)[0];
            totalPriceCell.innerText = item.price * item.count;
        });
    }

    $("#field_product_id").on('change', function(e) {
            product_id = $("#field_product_id").val();
            people_id =  $("#field_people_id").val();
            $("#add-to-cart-button").prop("disabled", !(people_id && product_id));
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
        shoppingCart.removeItemFromCartAll(productId);
        renderCart();
    });

    $(document).on("change",'.changeQuantity', function(e){
        e.preventDefault();
        var productId = event.target.getAttribute('productId');
        var newQuantity = event.target.value;
        shoppingCart.updateItemQuantityInCart(productId, newQuantity);
        updatePriceInRow();
        renderCart();
    });

    $(document).on("keydown keyup keypress",'.changeQuantity', function(e){
        if (e.which == 13) e.preventDefault();
    });

    $(document).on("click",'#submitShoppingCart', function(e){
        var cart = shoppingCart.listCart();
        var people_id = $("#field_people_id").val();
        $.ajax({
            type: "post",
            url: "ajax.php?file=check_out",
            data: {
                cart: JSON.stringify(cart),
                people_id: people_id
            },
            dataType: "json",
            success: function(result) {
                shoppingCart.clearCart();
                renderCart();
                if (result.success) {
                    window.location = "?action=check_out&people_id=" + people_id;
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