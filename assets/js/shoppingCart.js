$(document).ready(function() {
    // =============================
    // Configuration constants
    // =============================
    const CART_EXPIRATION_HOURS = 2;
    const CART_EXPIRATION_MS = CART_EXPIRATION_HOURS * 60 * 60 * 1000;
    const SESSION_KEY = 'shopping_cart_session_active';
    const SHOPPING_CART_PREFIX = 'shopping_cart_';

    // =============================
    // Helper functions
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

    // Show cart restored notification
    function showCartRestoredNotification(itemCount) {
        var itemText = itemCount === 1 ? 'item' : 'items';
        
        if (typeof noty === 'function') {
            noty({
                text: "Welcome back! Your shopping cart has been restored with " + itemCount + " " + itemText + ".",
                type: "information",
                closeWith: ['click'],
                timeout: 4000
            });
        }
    }

    var shoppingCart = (function() {
        // =============================
        // Private methods and properties
        // =============================
        var cart = [];
        var notificationShown = false;
        var initialCartExisted = false;
        var isNewSession = isNewBrowserSession();
        
        // Constructor
        function Item(id, name, nameWithoutPrice, count, price) {
            this.id = id;
            this.name = name;
            this.nameWithoutPrice = nameWithoutPrice;
            this.price = price;
            this.count = count;
        }
        
        // Save cart with timestamp to localStorage
        function saveCart() {
            var peopleId = getCurrentPeopleId();
            if (!peopleId) return;
            
            try {
                if (typeof(Storage) !== "undefined" && window.localStorage) {
                    var cartData = {
                        cart: cart,
                        timestamp: new Date().getTime()
                    };
                    localStorage.setItem(SHOPPING_CART_PREFIX + peopleId, JSON.stringify(cartData));
                }
            } catch (e) {
                console.warn('Could not save cart to localStorage:', e);
            }
        }
        
        // Load cart with expiration check and better error handling
        function loadCart(pid) {
            var peopleId = pid || getCurrentPeopleId();
            if (!peopleId) return { hasData: false, isExpired: false };
            
            try {
                if (typeof(Storage) === "undefined" || !window.localStorage) {
                    cart = [];
                    return { hasData: false, isExpired: false };
                }

                var storedData = localStorage.getItem(SHOPPING_CART_PREFIX + peopleId);
                
                if (!storedData) {
                    cart = [];
                    return { hasData: false, isExpired: false };
                }

                var parsedData = JSON.parse(storedData);
                
                // Check if data includes timestamp
                if (parsedData.timestamp && parsedData.cart) {
                    var now = new Date().getTime();
                    var isExpired = (now - parsedData.timestamp) > CART_EXPIRATION_MS;
                    
                    if (isExpired) {
                        localStorage.removeItem(SHOPPING_CART_PREFIX + peopleId);
                        cart = [];
                        return { hasData: true, isExpired: true };
                    }
                    
                    cart = parsedData.cart || [];
                    return { hasData: cart.length > 0, isExpired: false };
                } else {
                    // Handle legacy format
                    if (Array.isArray(parsedData)) {
                        cart = parsedData;
                        return { hasData: cart.length > 0, isExpired: true };
                    } else {
                        cart = [];
                        return { hasData: true, isExpired: true };
                    }
                }
            } catch (error) {
                console.warn('Error loading cart:', error);
                try {
                    localStorage.removeItem(SHOPPING_CART_PREFIX + peopleId);
                } catch (e) {
                    console.warn('Could not clear corrupted cart data:', e);
                }
                cart = [];
                return { hasData: false, isExpired: false };
            }
        }

        // Initialize cart on module load if we have people_id
        if (getCurrentPeopleId()) {
            var initialLoad = loadCart();
            initialCartExisted = initialLoad.hasData && !initialLoad.isExpired;
        }

        // =============================
        // Public methods and properties
        // =============================
        var obj = {};
        
        // Add to cart
        obj.addItemToCart = function(id, name, price, count) {
            loadCart();
            
            for (var i = 0; i < cart.length; i++) {
                if (cart[i].id === id) {
                    cart[i].count = parseInt(cart[i].count) + parseInt(count);
                    saveCart();
                    return;
                }
            }
            
            var nameWithoutPrice = name.indexOf('(') !== -1 ? name.substring(0, name.indexOf('(')) : name;
            var item = new Item(id, name, nameWithoutPrice, parseInt(count), parseInt(price));
            cart.push(item);
            saveCart();
        };

        // Set count for item
        obj.setCountForItem = function(name, count) {
            for (var i = 0; i < cart.length; i++) {
                if (cart[i].name === name) {
                    cart[i].count = count;
                    break;
                }
            }
            saveCart();
        };

        // Remove item from cart (decrease count by 1)
        obj.removeItemFromCart = function(id) {
            for (var i = 0; i < cart.length; i++) {
                if (cart[i].id === id) {
                    cart[i].count--;
                    if (cart[i].count === 0) {
                        cart.splice(i, 1);
                    }
                    break;
                }
            }
            saveCart();
        };
      
        // Remove all items from cart
        obj.removeItemFromCartAll = function(id) {
            for (var i = 0; i < cart.length; i++) {
                if (cart[i].id === id) {
                    cart.splice(i, 1);
                    break;
                }
            }
            saveCart();
        };

        // Update quantity of one product in cart
        obj.updateItemQuantityInCart = function(id, newQuantity) {
            for (var i = 0; i < cart.length; i++) {
                if (cart[i].id === id) {
                    cart[i].count = parseInt(newQuantity);
                    break;
                }
            }
            saveCart();
        };
      
        // Clear cart
        obj.clearCart = function() {
            cart = [];
            saveCart();
        };
      
        // Count total items in cart
        obj.totalCount = function() {
            var totalCount = 0;
            for (var i = 0; i < cart.length; i++) {
                totalCount += cart[i].count;
            }
            return totalCount;
        };
      
        // Calculate total cart value
        obj.totalCart = function() {
            var totalCart = 0;
            for (var i = 0; i < cart.length; i++) {
                totalCart += cart[i].price * cart[i].count;
            }
            return Number(totalCart.toFixed(2));
        };
      
        // List cart items with totals
        obj.listCart = function() {
            var cartCopy = [];
            for (var i = 0; i < cart.length; i++) {
                var item = cart[i];
                var itemCopy = {};
                for (var p in item) {
                    if (item.hasOwnProperty(p)) {
                        itemCopy[p] = item[p];
                    }
                }
                itemCopy.total = Number(item.price * item.count).toFixed(2);
                cartCopy.push(itemCopy);
            }
            return cartCopy;
        };
      
        // Expose loadCart for external use
        obj.loadCart = loadCart;
        
        // Notification and session management
        obj.isNotificationShown = function() {
            return notificationShown;
        };
        
        obj.setNotificationShown = function(value) {
            notificationShown = value;
        };
        
        obj.resetNotificationFlag = function() {
            notificationShown = false;
        };
        
        obj.hadInitialCart = function() {
            return initialCartExisted;
        };
        
        obj.isNewSession = function() {
            return isNewSession;
        };

        return obj;
    })();

    // Make shoppingCart globally accessible
    window.shoppingCart = shoppingCart;

    // Enhanced renderCart with comprehensive logic
    function renderCart() {
        var loadResult = shoppingCart.loadCart();
        var peopleId = getCurrentPeopleId();
        
        // Wait for required elements to load
        if ($("#shopping_cart").length === 0 && $("#shopping_cart_outer_div").length > 0) {
            setTimeout(renderCart, 100);
            return loadResult;
        }
        
        // Show/hide cart based on items and people_id
        if (shoppingCart.totalCount() && peopleId) {
            $('#shopping_cart_outer_div').removeClass('hidden');
            $("[id=ajax-content]").show();
        } else {
            $('#shopping_cart_outer_div').addClass('hidden');
            if (!peopleId) {
                $("[id=ajax-content]").hide();
            }
        }

        // Clear existing cart rows
        $("#shopping_cart").find("tr:gt(0)").remove();
        var cartItems = shoppingCart.listCart();
        
        // Show notification for restored cart (only for new sessions)
        if (!shoppingCart.isNotificationShown() && 
            shoppingCart.isNewSession() && 
            shoppingCart.hadInitialCart() && 
            loadResult.hasData && 
            !loadResult.isExpired && 
            cartItems.length > 0) {
            
            showCartRestoredNotification(shoppingCart.totalCount());
            shoppingCart.setNotificationShown(true);
        }
        
        // Show expiration message if cart was expired
        if (loadResult.isExpired && typeof noty === 'function') {
            noty({
                text: "Your cart has expired and been cleared.",
                type: "warning",
                closeWith: ['click'],
                timeout: 3000
            });
        }
        
        // Render cart items
        cartItems.forEach(function(item) {
            var tableRef = document.getElementById("shopping_cart");
            if (!tableRef) return;
            
            var tr = jQuery('<tr/>').appendTo(tableRef);

            jQuery('<td/>').text(item.name).appendTo(tr);

            var amountCell = jQuery('<td/>').appendTo(tr);
            jQuery('<input/>', {
                'type': 'number',
                'class': 'form-control valid changeQuantity',
                'data-testid': 'changeQuantity',
                'step': '1',
                'min': '1',
                'productid': item.id,
                'value': item.count
            }).appendTo(amountCell);

            jQuery('<td/>', {
                'data-testid': 'price'
            }).text(item.price).appendTo(tr);

            jQuery('<td/>', {
                'id': 'totalSum_' + item.id,
                'data-testid': 'totalPrice'
            }).text(item.price * item.count).appendTo(tr);

            var deleteCell = jQuery('<td/>').appendTo(tr);
            var deleteCellButton = jQuery('<button/>', {
                'type': 'button',
                'class': 'btn btn-sm deleteFromCart',
                'data-testid': 'deleteFromCart',
                'productid': item.id
            }).appendTo(deleteCell);
            
            jQuery('<i/>', {
                'class': 'fa fa-trash-o'
            }).appendTo(deleteCellButton);
        });
        
        updateCartRelatedElements();
        return loadResult;
    }

    // Enhanced updateCartRelatedElements with comprehensive safety checks
    function updateCartRelatedElements() {
        var dropcredit = $("#dropcredit").data("dropCredit") || 0;

        // Update cart value and remaining credit with safety checks
        var cartValueElement = $("#cartvalue_aside");
        if (cartValueElement.length > 0) {
            cartValueElement.text(shoppingCart.totalCart());
        }
        
        var creditValueElement = $("#creditvalue_aside");
        if (creditValueElement.length > 0) {
            creditValueElement.text(dropcredit - shoppingCart.totalCart());
        }
        
        // Show/hide cart value
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

        // Enable/disable submit button
        var isCartEmpty = shoppingCart.totalCount() === 0;
        $('#submitShoppingCart').prop("disabled", isCartEmpty || !enough_tokens);
    }

    // Enhanced updatePriceInRow
    function updatePriceInRow() {
        shoppingCart.loadCart();
        shoppingCart.listCart().forEach(function(item) {
            var totalPriceCell = $("#totalSum_" + item.id);
            if (totalPriceCell.length > 0) {
                totalPriceCell.text(item.price * item.count);
            }
        });
        updateCartRelatedElements();
    }

    // Event Handlers
    $("#field_product_id").on('change', function(e) {
        var product_id = $("#field_product_id").val();
        var people_id = getCurrentPeopleId();
        $("#add-to-cart-button").prop("disabled", !(people_id && product_id));
    });

    $("#field_people_id").on('change', function(e) {
        var people_id = $("#field_people_id").val();
        if (people_id === "") {
            $("[id=ajax-content]").hide();
        } else {
            $("[id=ajax-content]").show();
            // Load cart for new person - only if not being called from selectFamily
            if (!$("body").hasClass("loading")) {
                shoppingCart.resetNotificationFlag();
                shoppingCart.loadCart(people_id);
                setTimeout(function() {
                    renderCart();
                }, 100);
            }
        }
    });

    // Enhanced add to cart with better validation
    $("#add-to-cart-button").click(function(e) {
        e.preventDefault();

        var count = $(this).closest("form").find("input[name='count']").val();
        var productId = $("#field_product_id").val();
        var productName = $("#select2-chosen-2").length > 0 ? $("#select2-chosen-2")[0].innerText : '';
        var price = parseInt($("#field_product_id").children(":selected").data("price")) || 0;
        
        shoppingCart.addItemToCart(productId, productName, price, parseInt(count));
        renderCart();

        // Reset form
        $('#field_product_id').val(null).trigger('change');
        $("#field_count").val(1);
    });

    $(document).on("click", '.deleteFromCart', function(e) {
        var productId = e.target.getAttribute('productId');
        if (!productId) {
            // Event triggered by the trash icon rather than button itself
            productId = e.target.parentElement.getAttribute('productId');
        }
        shoppingCart.removeItemFromCartAll(productId);
        renderCart();
    });

    // Enhanced quantity change with validation
    $(document).on("change", '.changeQuantity', function(e) {
        e.preventDefault();
        var productId = e.target.getAttribute('productId');
        var newQuantity = e.target.value;
        if (newQuantity > 0) {
            shoppingCart.updateItemQuantityInCart(productId, newQuantity);
            updatePriceInRow();
        }
    });

    $(document).on("keydown keyup keypress", '.changeQuantity', function(e) {
        if (e.which === 13) e.preventDefault();
    });

    // Enhanced checkout with better error handling
    $(document).on("click", '#submitShoppingCart', function(e) {
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
                                    if (typeof execReload === 'function') {
                                        execReload(result.redirect);
                                    }
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
                noty({
                    text: "We cannot connect to the Boxtribute server.<br> Do you have internet?",
                    type: "error"
                });
            }
        });
    });

    // Final initialization
    setTimeout(function() {
        initializePeopleIdFromUrl();
        
        var peopleId = getCurrentPeopleId();
        
        if (peopleId) {
            // Only render cart if the required elements exist
            if ($("#shopping_cart").length > 0 || $("#shopping_cart_outer_div").length > 0) {
                renderCart();
            } else {
                // If elements don't exist yet, try again later
                setTimeout(function() {
                    renderCart();
                }, 500);
            }
        } else {
            renderCart();
        }
    }, 100);

    // Make renderCart globally accessible
    window.renderCart = renderCart;
});