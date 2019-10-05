const FAMILY1 = "McGregor";
const FAMILY2 = "Tonon";
const FAMILY3 = "Gracie";
const FAMILY_WITHOUT_TOKENS = "Without";

const PRODUCT1 = "Shampoo";
const PRODUCT2 = "Rice";
const PRODUCT3 = "Jeans";
const PRODUCT4 = "Trainers";
const PRODUCT5 = "T-Shirt";
const PRODUCT6 = "Sleeping Bag";
const PRODUCT_FREE = "Diapers";

describe('Checkout tests', () => {

  beforeEach(() => {
    cy.loginAsVolunteer();
    cy.visit('/?action=check_out');
  });

  function clickAddToCartButton() {
    getAddToCartButton().click();
  }

  function typeProductQuantity(quantity) {
    cy.get("input[data-testid='productQuantityInput'").clear().type(quantity);
  }

  function getCartValue() {
    return cy.get("span[data-testid='cartvalue_aside']").invoke("text");
  }

  function clickCheckoutSubmitButton() {
    cy.get("button[data-testid='submitShoppingCart']").click();
  }

  function getChangeQuantityCartInputs() {
    return cy.get("input[data-testid='changeQuantity']");
  }

  function getAddToCartButton() {
    return cy.get("button[data-testid='add-to-cart-button']");
  }

  function getDeleteFromCartButtons() {
    return cy.get("button[data-testid='deleteFromCart']");
  }

  function getFamilyTokensSpan() {
    return cy.get("span[data-testid='dropcredit']");
  }

  function getFamilyCredit() {
    return cy.get("div[class='info-aside'] p[class='familycredit']");
  }

  function checkoutFormIsResetted() {
    cy.get("body").then($body => {
      expect($body.find("select[data-placeholder='Please select']").length).to.equal(2);
    });
  }

  it('Left panel navigation', () => {
	  cy.visit('/');
	  cy.get("a[class='menu_check_out']").last().contains("Checkout").click();
    cy.get("button[data-testid='submitShoppingCart']").should("be.visible");
    cy.verifyActiveSideMenuNavigation('menu_check_out');
    cy.getSelectedValueInDropDown("people_id").contains("Please select").should('exist');
    cy.getSelectedValueInDropDown("product_id").contains("Please select").should('exist');
  });

  it('Select family in dropdown', () => {
    cy.selectOptionByText("people_id", FAMILY1);
    cy.getSelectedValueInDropDown("people_id").contains(FAMILY1).should('exist');
    getFamilyCredit().should('be.visible');
    getAddToCartButton().should('be.disabled');
  });

  it('Select product in dropdown', () => {
    cy.selectOptionByText("product_id", PRODUCT1);
    cy.getSelectedValueInDropDown("product_id").contains(PRODUCT1).should('exist');
    getFamilyCredit().should('not.be.visible');
    getAddToCartButton().should('be.disabled');
  });

  it('Add first item to cart', () => {
    cy.selectOptionByText("people_id", FAMILY3);
    cy.selectOptionByText("product_id", PRODUCT6);
    typeProductQuantity(5);
    clickAddToCartButton();
    // family should stay selected
    cy.getSelectedValueInDropDown("people_id").contains(FAMILY3).should('exist');
    // product dropdown should get resetted
    cy.getSelectedValueInDropDown("product_id").contains("Please select").should('exist');
    getFamilyTokensSpan().should('exist');  // isn't necessarily visible in mobile version
    cy.contains("h2", "Shopping cart");
    cy.get("body").then($body => {
      expect($body.find("table[data-testid='shopping_cart'] tr").length).to.equal(2);
    });
    cy.contains("td", PRODUCT6).should('exist');
  });

  it('Adding several products to cart', () => {
    cy.selectOptionByText("people_id", FAMILY2);
    cy.selectOptionByText("product_id", PRODUCT6);
    typeProductQuantity(2);
    clickAddToCartButton();
    cy.selectOptionByText("product_id", PRODUCT2);
    typeProductQuantity(4);
    clickAddToCartButton();
    cy.selectOptionByText("product_id", PRODUCT4);
    typeProductQuantity(1);
    clickAddToCartButton();
    getFamilyTokensSpan().should('exist');  // isn't necessarily visible in mobile version
    cy.contains("h2", "Shopping cart");
    cy.get("body").then($body => {
      expect($body.find("table[data-testid='shopping_cart'] tr").length).to.equal(4);
    });
    cy.contains("td", PRODUCT6).should('exist');
    cy.contains("td", PRODUCT2).should('exist');
    cy.contains("td", PRODUCT4).should('exist');
    cy.get("body").then($body => {
      // number of rows in table should match number of added products
      assert.isAtLeast($body.find("table[data-testid='shopping_cart'] tr").length, 3)
      //expect($body.find("table[data-testid='shopping_cart'] tr").length).to.equal(4);
    });
  });

  it('Cart value - adding one more product', () => {
    let initialCartPrice = 100;
    let finalCartPrice = 200;
    cy.selectOptionByText("people_id", FAMILY2);
    cy.selectOptionByText("product_id", PRODUCT2);
    typeProductQuantity(4);
    clickAddToCartButton();
    getCartValue().then(cartValue => {
      expect(parseInt(cartValue)).to.equal(initialCartPrice);
      cy.selectOptionByText("product_id", PRODUCT4);
      typeProductQuantity(1);
      clickAddToCartButton();
      getCartValue();
    })
      .then(currentCartValue => {
        expect(parseInt(currentCartValue)).to.equal(finalCartPrice);
      });
  });

  it('Cart value - deleting one whole product', () => {
    let testFamily = "McGregor";
    let initialCartPrice = 250;
    let finalCartPrice = 200;
    cy.selectOptionByText("people_id", testFamily);
    cy.selectOptionByText("product_id", PRODUCT2);
    typeProductQuantity(2);
    clickAddToCartButton();
    cy.selectOptionByText("product_id", PRODUCT4);
    typeProductQuantity(2);
    clickAddToCartButton();
    getCartValue().then(cartValue => {
      expect(parseInt(cartValue)).to.equal(initialCartPrice);
      getDeleteFromCartButtons().first().click();
      getCartValue();
    })
      .then(currentCartValue => {
        expect(parseInt(currentCartValue)).to.equal(finalCartPrice);
      });
  });

  it('Cart value - incrementing product count', () => {
    let initialCartPrice = 60;
    let finalCartPrice = 90;
    cy.selectOptionByText("people_id", FAMILY2);
    cy.selectOptionByText("product_id", PRODUCT5);
    typeProductQuantity(2);
    clickAddToCartButton();
    getCartValue().then(prevCartValue => {
      expect(parseInt(prevCartValue)).to.equal(initialCartPrice);
      getChangeQuantityCartInputs().first().clear().type(3);
      cy.contains("h2", "Shopping cart").click(); //trick to submit previous field onBlur
      getCartValue();
    })
      .then(currentCartValue => {
        expect(parseInt(currentCartValue)).to.equal(finalCartPrice);
      });
  });

  it('Cart value - decrementing product count', () => {
    let initialCartPrice = 60;
    let finalCartPrice = 40;
    cy.selectOptionByText("people_id", FAMILY2);
    cy.selectOptionByText("product_id", PRODUCT1);
    typeProductQuantity(3);
    clickAddToCartButton();
    getCartValue().then(prevCartValue => {
      expect(parseInt(prevCartValue)).to.equal(initialCartPrice);
      getChangeQuantityCartInputs().first().clear().type(2);
      cy.contains("h2", "Shopping cart").click(); //trick to submit previous field onBlur
      getCartValue();
    })
      .then(currentCartValue => {
        expect(parseInt(currentCartValue)).to.equal(finalCartPrice);
      });
  });

  // NOT FINISHED 
  // it('Cart value bigger than family tokens', () => {
  //   let currentCartValue;
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
    cy.selectOptionByText("people_id", FAMILY1);
    cy.selectOptionByText("product_id", PRODUCT3);
    typeProductQuantity(2);
    clickAddToCartButton();
    clickCheckoutSubmitButton();
    cy.notificationWithTextIsVisible("Shopping cart successfully submitted!");
    checkoutFormIsResetted();
  });

  it('Add zero value product & submit cart', () => {
    cy.selectOptionByText("people_id", FAMILY3);
    cy.selectOptionByText("product_id", PRODUCT_FREE);
    typeProductQuantity(10);
    getAddToCartButton().should('not.be.disabled');
    clickAddToCartButton();
    clickCheckoutSubmitButton();
    cy.notificationWithTextIsVisible("Shopping cart successfully submitted!");
    checkoutFormIsResetted();
  });

  it('Completely tokenless market (product & families have no tokens)', () => {
    cy.selectOptionByText("people_id", FAMILY_WITHOUT_TOKENS);
    cy.selectOptionByText("product_id", PRODUCT_FREE);
    typeProductQuantity(15);
    getAddToCartButton().should('not.be.disabled');
    clickAddToCartButton();
    clickCheckoutSubmitButton();
    cy.notificationWithTextIsVisible("Shopping cart successfully submitted!");
    checkoutFormIsResetted();
  });

  // // NOT FINISHED YET
  // it('Give tokens', () => {
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