describe('Checkout tests', () => {
  let testAdmin;
  let testCoordinator;
  let testUser;
  let testPwd;
  
  before(function() {
      cy.getAdminUser().then(($result) => {
        testAdmin = $result.testAdmin;
        testPwd = $result.testPwd;
      });

      cy.getCoordinatorUser().then(($result) => {
        testCoordinator = $result.testCoordinator;
        testPwd = $result.testPwd;
      });

      cy.getVolunteerUser().then(($result) => {
        testUser = $result.testUser;
        testPwd = $result.testPwd;
      });
  });

  beforeEach(function(){
  });

  function navigateToCheckout(){
      cy.LoginAjax(testAdmin, testPwd, true);
      cy.visit('/');
      cy.get("a[class='menu_check_out']").last().contains("Checkout").click();
  }

  function selectRandomProduct(){
    clickProductsDropdown();
    cy.get("body").then($body => {
      let randomOption = Math.floor(Math.random() * ($body.find("ul[class='select2-results'] li").length - 1)) + 1;
      getDropdownOptions().eq(randomOption).click();
    });
  }

  function selectRandomFamily(){
    getFamilyDropdown().click();
    cy.get("body").then($body => {
      let randomOption = Math.floor(Math.random() * ($body.find("ul[class='select2-results'] li").length - 1)) + 1;
      getDropdownOptions().eq(randomOption).click();
    });
  }

  function selectCheckoutDropdowns(){
    selectRandomFamily();
    selectRandomProduct();            
  }

  function randomizeCartContent(){
    selectRandomFamily();
    let itemsCountInCart = Math.floor(Math.random() * 5) + 1;
    for (let i=1; i<= itemsCountInCart; i++){
      selectRandomProduct();
      typeProductQuantity(i);
      clickAddToCartButton();
    }
    return itemsCountInCart;
  }

  function selectProductWorthZero(){
    clickProductsDropdown();
    getDropdownOptions().contains("Diapers Unisex Baby").click();
  }

  function clickAddToCartButton(){
    getAddToCartButton().click();
  }

  function typeProductQuantity(quantity){
    cy.get("input[id='field_count'").clear().type(quantity);
  }

  function getCartValue(){
    return cy.get("span[data-testid='cartvalue_aside']").invoke("text");
  }

  function getFamilyTokens(){
    return getFamilyTokensSpan().invoke("text");
  }

  function clickCheckoutSubmitButton(){
    cy.get("button[data-testid='submitShoppingCart']").click();
  }

  function selectFamilyWithZeroTokens(){
    getFamilyDropdown().click();
    getDropdownOptions().contains("WithoutTokens").click();
  }

  function getFamilyDropdown(){
    return cy.get("div[id='s2id_field_people_id']");
  }

  function getDropdownOptions(){
    return cy.get("ul[class='select2-results'] li");
  }

  function clickProductsDropdown(){
    cy.get("div[id='s2id_field_product_id']").click();
  }

  function getSelectedProduct(){
    return cy.get("span[id='select2-chosen-2']");
  }

  function getChangeQuantityCartInputs(){
    return cy.get("input[data-testid='changeQuantity']");
  }

  function getAddToCartButton(){
    return cy.get("button[data-testid='add-to-cart-button']");
  }

  function getDeleteFromCartButtons(){
    return cy.get("button[data-testid='deleteFromCart']");
  }

  function getFamilyTokensSpan(){
    return cy.get("span[data-testid='dropcredit']");
  }

  function getFamilyCredit(){
    return cy.get("div[class='info-aside'] p[class='familycredit']");
  }

  it('Left panel navigation', () => {
      navigateToCheckout();
      // shopping cart elements
      cy.get("button[data-testid='submitShoppingCart']").should("be.visible");
      // correct side panel navigation
      cy.get("a[class='menu_check_out']").last().parent().should("have.class","active");
      // empty dropdowns
      cy.get("body").then($body => {
          expect($body.find("select[data-placeholder='Please select']").length).to.equal(2);
      });
  });

  it('Select family in dropdown', () => {
    navigateToCheckout();
    getFamilyDropdown().click();
    // get name of first beneficiary before clicking it
    getDropdownOptions().first().find("div").invoke('text').then((text) => {
      getDropdownOptions().first().click();
      // name should be visibly selected
      getFamilyCredit().contains(text.trim()).should('be.visible');
    });
    // information container on the side with family credit should be shown
    cy.get("div[class='info-aside'] p[class='familycredit']").should('be.visible');
    //add button should be disabled
    getAddToCartButton().should('be.disabled');
  });

  it('Select product in dropdown', () => {
    navigateToCheckout();
    clickProductsDropdown();
    // get name of last product before clicking it
    getDropdownOptions().last().find("div").invoke('text').then((selectedProduct) => {
      getDropdownOptions().last().click();
      // name should be visibly selected
      getSelectedProduct().contains(selectedProduct.trim()).should('be.visible');
    });
    // information container on the side with family credit should not be shown
    getFamilyCredit().should('not.be.visible');
    //add button should be disabled
    getAddToCartButton().should('be.disabled');
  });

  it('Add first item to cart', () => {
    navigateToCheckout();
    selectCheckoutDropdowns();
    getSelectedProduct().invoke('text').then((selectedProduct) => {
      typeProductQuantity(5);
      clickAddToCartButton();
      // product dropdown gets cleared after adding item to cart
      getSelectedProduct().contains("Please select").should('exist');
      // shopping cart value should exist
      getFamilyTokensSpan().should('exist');  // isn't necessarily visible in mobile version 
      // shopping cart header should appear
      cy.contains("h2","Shopping cart");
      // shopping cart has 2 rows (header and one product)
      cy.get("body").then($body => {
        expect($body.find("table[data-testid='shopping_cart'] tr").length).to.equal(2);
      });
      // shopping cart should contain a table cell row with the product
      cy.contains("td",selectedProduct.trim()).should('exist');
    });
  });
  
  it('Adding multiple products to cart', () => {
    navigateToCheckout();
    let addedProductsCount = randomizeCartContent();
    cy.get("body").then($body => {
      // number of rows in table should match number of added products
      assert.isAtLeast($body.find("table[data-testid='shopping_cart'] tr").length,addedProductsCount)
      //expect($body.find("table[data-testid='shopping_cart'] tr").length).to.equal(addedProductsCount+1);
    });
  }); 

  it('Cart value - adding one more product', () => {
    let productPrice;
    let expectedCartPrice;
    navigateToCheckout();
    randomizeCartContent();
    clickProductsDropdown();
    getDropdownOptions().last().click();
    getSelectedProduct().invoke('text').then((selectedProduct) => {
      return cy.contains("option",selectedProduct.trim())
    })
    .then($selectedOption => {
      productPrice = $selectedOption.data("price");
      getCartValue();
    })
    .then((previousCartValue) =>{
      typeProductQuantity(1);
      clickAddToCartButton();
      expectedCartPrice = parseInt(previousCartValue) + productPrice;
      getCartValue();
    })
    .then(currentCartValue => {
      expect(parseInt(currentCartValue)).to.equal(expectedCartPrice);
    });
  });

  it('Cart value - deleting one whole product', () => {
    navigateToCheckout();
    randomizeCartContent();
    let loweredPriceBy;
    let expectedCartPrice;
    cy.get("td[data-testid='totalPrice']").first().invoke("text").then(totalProductPrice => {
      loweredPriceBy = parseInt(totalProductPrice);
      getCartValue();
    })
    .then(prevCartValue => {
      expectedCartPrice =  parseInt(prevCartValue) - loweredPriceBy;
      getDeleteFromCartButtons().first().click();
      getCartValue();
    })
    .then(currentCartValue => {
      expect(parseInt(currentCartValue)).to.equal(expectedCartPrice);
    });
  });  

  it('Cart value - incrementing product count', () => {
    navigateToCheckout();
    randomizeCartContent();
    let increasedPriceBy;
    let expectedCartPrice;
    cy.get("td[data-testid='price']").first().invoke("text").then(pricePerItem => {
      increasedPriceBy = parseInt(pricePerItem);
      getCartValue();
    })
    .then(prevCartValue => {
      expectedCartPrice =  parseInt(prevCartValue) + increasedPriceBy;
      return getChangeQuantityCartInputs().first().invoke("val");;
    })
    .then(currentQuantity => {
      getChangeQuantityCartInputs().first().clear().type(parseInt(currentQuantity)+1);
      cy.contains("h2","Shopping cart").click(); //trick to submit previous field onBlur
      getCartValue();
    })
    .then(currentCartValue => {
      expect(parseInt(currentCartValue)).to.equal(expectedCartPrice);
    });
  });

  it('Cart value - decrementing product count', () => {
    navigateToCheckout();
    randomizeCartContent();
    let increasedPriceBy;
    let expectedCartPrice;
    cy.get("td[data-testid='price']").last().invoke("text").then(pricePerItem => {
      increasedPriceBy = parseInt(pricePerItem);
      getCartValue();
    })
    .then(prevCartValue => {
      expectedCartPrice =  parseInt(prevCartValue) - increasedPriceBy;
      return getChangeQuantityCartInputs().last().invoke("val");;
    })
    .then(currentQuantity => {
      getChangeQuantityCartInputs().last().clear().type(parseInt(currentQuantity)-1);
      cy.contains("h2","Shopping cart").click(); //trick to submit previous field
      getCartValue();
    })
    .then(currentCartValue => {
      expect(parseInt(currentCartValue)).to.equal(expectedCartPrice);
    });
  });

  // NOT FINISHED 
  // it('Cart value bigger than family tokens', () => {
  //   let currentCartValue;
  //   navigateToCheckout();
  //   randomizeCartContent();
  //   getCartValue().then(cartValue => {
  //     currentCartValue = parseInt(cartValue);
  //     getFamilyTokens();
  //   })
  //   .then(familyTokens => {
  //     while(parseInt(familyTokens)>currentCartValue){
  //       // debugger;
  //          clickProductsDropdown();
  //       // getDropdownOptions().first().click();
  //       // typeProductQuantity(10);
  //       // clickAddToCartButton();
  //     }
  //   })
  // });
  

  it('Add non-zero value products & submit cart', () => {
    navigateToCheckout();
    randomizeCartContent();
    clickCheckoutSubmitButton();
    // visible notification
    cy.NotificationWithTextIsVisible("Shopping cart successfully submitted!");
    // dropdowns are empty again
    cy.get("body").then($body => {
      expect($body.find("select[data-placeholder='Please select']").length).to.equal(2);
    });
  });

  it('Add zero value product & submit cart', () => {
    navigateToCheckout();
    selectRandomFamily();
    selectProductWorthZero();
    let randomCount = Math.floor(Math.random() * 10 - 1) + 1;
    typeProductQuantity(randomCount);
    // add to cart should be enabled even if product has 0 tokens value
    getAddToCartButton().should('not.be.disabled');
    clickAddToCartButton();
    clickCheckoutSubmitButton();
    // visible notification
    cy.NotificationWithTextIsVisible("Shopping cart successfully submitted!");
    // dropdowns are empty again
    cy.get("body").then($body => {
      expect($body.find("select[data-placeholder='Please select']").length).to.equal(2);
    });
  });

  it('Completely tokenless market (product & families have no tokens)', () => {
    navigateToCheckout();
    selectFamilyWithZeroTokens();
    selectProductWorthZero();
    let randomCount = Math.floor(Math.random() * 10) + 1;
    typeProductQuantity(randomCount);
    // add to cart should be enabled even if family has zero tokens
    getAddToCartButton().should('not.be.disabled');
    clickAddToCartButton();
    clickCheckoutSubmitButton();
    // visible notification
    cy.NotificationWithTextIsVisible("Shopping cart successfully submitted!");
    // dropdowns are empty again
    cy.get("body").then($body => {
      expect($body.find("select[data-placeholder='Please select']").length).to.equal(2);
    });
  });

  // // NOT FINISHED YET
  // it('Give tokens', () => {
  //   navigateToCheckout();
  //   getFamilyDropdown().click();
  //   getDropdownOptions().first().find("div").invoke('text').then((text) => {
  //     let familyName = text.trim();
  //     debugger;
  //     getDropdownOptions().first().click();
  //     // cy.get("span[data-testid='giveTokensButton']").click().then(() => {
  //     //   cy.get("input[type='text']").contains(familyName);
  //     // });
  //     // name should be in families field (doesn't work tho)
  //     cy.get("input[type='text']").contains(familyName);
  //   });
  // });
});