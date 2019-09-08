describe('Login tests - Mobile', () => {
  let testAdmin;
  let testCoordinator;
  let testUser;
  let testNotActivatedUser;
  let testExpiredUser;
  let testDeletedUser;
  let testPwd;
  let testWrongPwd;
  let incorrectLoginNotif;
  let genericErrLoginNotif;
  let successPwdChangeNotif;
  let testOrg;
  
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

      cy.getLoginScenarioUsers().then(($result) => {
          testExpiredUser = $result.testExpiredUser;
          testNotActivatedUser = $result.testNotActivatedUser;
          testDeletedUser = $result.testDeletedUser;
          testPwd = $result.testPwd;
          testWrongPwd = $result.testWrongPwd;
      });

      cy.getLoginNotifications().then(($result) => {
        incorrectLoginNotif = $result.incorrectLoginNotif;
        genericErrLoginNotif = $result.genericErrLoginNotif;
        successPwdChangeNotif = $result.successPwdChangeNotif;
      });

      cy.getTestOrgName().then(($result) => {
        testOrg = $result.orgName;
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
    cy.get("div[id='s2id_field_product_id']").click();
    cy.get("body").then($body => {
      let randomOption = Math.floor(Math.random() * ($body.find("ul[class='select2-results'] li").length - 1)) + 1;
      cy.get("ul[class='select2-results'] li").eq(randomOption).click();
    });
  }

  function selectRandomFamily(){
    cy.get("div[id='s2id_field_people_id']").click();
    cy.get("body").then($body => {
      let randomOption = Math.floor(Math.random() * ($body.find("ul[class='select2-results'] li").length - 1)) + 1;
      cy.get("ul[class='select2-results'] li").eq(randomOption).click();
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
    cy.get("div[id='s2id_field_product_id']").click();
    cy.get("ul[class='select2-results'] li").contains("Diapers Unisex Baby").click();
  }

  function clickAddToCartButton(){
    cy.get("button[data-testid='add-to-cart-button']").click();
  }

  function typeProductQuantity(quantity){
    cy.get("input[id='field_count'").clear().type(quantity);
  }

  function getCartValue(){
    return cy.get("span[data-testid='cartvalue_aside']").invoke("text");
  }

  function getFamilyTokens(){
    return cy.get("span[data-testid='dropcredit']").invoke("text");
  }

  function clickCheckoutSubmitButton(){
    cy.get("button[data-testid='submitShoppingCart']").click();
  }

  function selectFamilyWithZeroTokens(){
    cy.get("div[id='s2id_field_people_id']").click();
    cy.get("ul[class='select2-results'] li").contains("WithoutTokens").click();
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
    cy.get("div[id='s2id_field_people_id']").click();
    // get name of first beneficiary before clicking it
    cy.get("ul[class='select2-results'] li").first().find("div").invoke('text').then((text) => {
      cy.get("ul[class='select2-results'] li").first().click();
      // name should be visibly selected
      cy.get("span[id='select2-chosen-1']").contains(text.trim()).should('be.visible');
    });
    // information container on the side with family credit should be shown
    cy.get("div[class='info-aside'] p[class='familycredit']").should('be.visible');
    //add button should be disabled
    cy.get("button[data-testid='add-to-cart-button']").should('be.disabled');
  });

  it('Select product in dropdown', () => {
    navigateToCheckout();
    cy.get("div[id='s2id_field_product_id']").click();
    // get name of last product before clicking it
    cy.get("ul[class='select2-results'] li").last().find("div").invoke('text').then((selectedProduct) => {
      cy.get("ul[class='select2-results'] li").last().click();
      // name should be visibly selected
      cy.get("span[id='select2-chosen-2']").contains(selectedProduct.trim()).should('be.visible');
    });
    // information container on the side with family credit should not be shown
    cy.get("div[class='info-aside'] p[class='familycredit']").should('not.be.visible');
    //add button should be disabled
    cy.get("button[data-testid='add-to-cart-button']").should('be.disabled');
  });

  it('Add first item to cart', () => {
    navigateToCheckout();
    selectCheckoutDropdowns();
      cy.get("span[id='select2-chosen-2']").invoke('text').then((selectedProduct) => {
      typeProductQuantity(5);
      clickAddToCartButton();
      // product dropdown gets cleared after adding item to cart
      cy.get("span[id='select2-chosen-2']").contains("Please select").should('exist');
      // shopping cart value should exist
      cy.get("span[data-testid='dropcredit']").should('exist');  // isn't necessarily visible in mobile version 
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

  it('Prevent adding negative count of items', () => {
    navigateToCheckout();
    selectCheckoutDropdowns();
    cy.get("button[data-testid='add-to-cart-button']").should('not.be.disabled');
    typeProductQuantity(-1);
    cy.get("button[data-testid='add-to-cart-button']").should('be.disabled');
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
    cy.get("div[id='s2id_field_product_id']").click();
    cy.get("ul[class='select2-results'] li").last().click();
    cy.get("span[id='select2-chosen-2']").invoke('text').then((selectedProduct) => {
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
      cy.get("button[data-testid='deleteFromCart']").first().click();
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
      return cy.get("input[data-testid='changeQuantity']").first().invoke("val");;
    })
    .then(currentQuantity => {
      cy.get("input[data-testid='changeQuantity']").first().clear().type(parseInt(currentQuantity)+1);
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
      return cy.get("input[data-testid='changeQuantity']").last().invoke("val");;
    })
    .then(currentQuantity => {
      cy.get("input[data-testid='changeQuantity']").last().clear().type(parseInt(currentQuantity)-1);
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
  //       // cy.get("div[id='s2id_field_product_id']").click();
  //       // cy.get("ul[class='select2-results'] li").first().click();
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
    cy.get("button[data-testid='add-to-cart-button']").should('not.be.disabled');
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
    cy.get("button[data-testid='add-to-cart-button']").should('not.be.disabled');
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
  //   cy.get("div[id='s2id_field_people_id']").click();
  //   cy.get("ul[class='select2-results'] li").first().find("div").invoke('text').then((text) => {
  //     let familyName = text.trim();
  //     debugger;
  //     cy.get("ul[class='select2-results'] li").first().click();
  //     // cy.get("span[data-testid='giveTokensButton']").click().then(() => {
  //     //   cy.get("input[type='text']").contains(familyName);
  //     // });
  //     // name should be in families field (doesn't work tho)
  //     cy.get("input[type='text']").contains(familyName);
  //   });
  // });
});