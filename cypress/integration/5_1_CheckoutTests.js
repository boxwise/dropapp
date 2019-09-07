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

    function selectCheckoutDropdowns(){
      cy.get("div[id='s2id_field_people_id']").click();
      cy.get("ul[class='select2-results'] li").first().click();
      cy.get("div[id='s2id_field_product_id']").click();
      cy.get("ul[class='select2-results'] li").last().click();
    }

    // it('Left panel navigation', () => {
    //     navigateToCheckout();
    //     // shopping cart elements
    //     cy.get("button[data-testid='submitShoppingCart']").should("be.visible");
    //     // correct side panel navigation
    //     cy.get("a[class='menu_check_out']").last().parent().should("have.class","active");
    //     // empty dropdowns
    //     cy.get("body").then($body => {
    //         expect($body.find("select[data-placeholder='Please select']").length).to.equal(2);
    //     });
    // });

    // it('Select family in dropdown', () => {
    //   navigateToCheckout();
    //   cy.get("div[id='s2id_field_people_id']").click();
    //   // get name of first beneficiary before clicking it
    //   cy.get("ul[class='select2-results'] li").first().find("div").invoke('text').then((text) => {
    //     cy.get("ul[class='select2-results'] li").first().click();
    //     // name should be visibly selected
    //     cy.get("span[id='select2-chosen-1']").contains(text.trim()).should('be.visible');
    //   });
    //   // information container on the side with family credit should be shown
    //   cy.get("div[class='info-aside'] p[class='familycredit']").should('be.visible');
    //   //add button should be disabled
    //   cy.get("button[data-testid='add-to-cart-button']").should('be.disabled');
    // });

    // it('Select product in dropdown', () => {
    //   navigateToCheckout();
    //   cy.get("div[id='s2id_field_product_id']").click();
    //   // get name of first product before clicking it
    //   cy.get("ul[class='select2-results'] li").last().find("div").invoke('text').then((selectedProduct) => {
    //     cy.get("ul[class='select2-results'] li").last().click();
    //     // name should be visibly selected
    //     cy.get("span[id='select2-chosen-2']").contains(selectedProduct.trim()).should('be.visible');
    //   });
    //   // information container on the side with family credit should not be shown
    //   cy.get("div[class='info-aside'] p[class='familycredit']").should('not.be.visible');
    //   //add button should be disabled
    //   cy.get("button[data-testid='add-to-cart-button']").should('be.disabled');
    // });

    // it('Add first item to cart', () => {
    //   navigateToCheckout();
    //   selectCheckoutDropdowns();
    //     cy.get("span[id='select2-chosen-2']").invoke('text').then((selectedProduct) => {
    //     cy.get("input[id='field_count'").clear().type(5);
    //     cy.get("button[data-testid='add-to-cart-button']").click();
    //     // product dropdown gets cleared after adding item to cart
    //     cy.get("span[id='select2-chosen-2']").contains("Please select").should('exist');
    //     // shopping cart value should exist
    //     cy.get("span[data-testid='dropcredit']").should('exist');  // isn't necessarily visible in mobile version 
    //     // shopping cart header should appear
    //     cy.contains("h2","Shopping cart");
    //     // shopping cart has 2 rows (header and one product)
    //     cy.get("body").then($body => {
    //       expect($body.find("table[data-testid='shopping_cart'] tr").length).to.equal(2);
    //     });
    //     // shopping cart should contain a table cell row with the product
    //     cy.contains("td",selectedProduct.trim()).should('exist');
    //   });
    // });

    // it('Prevent adding negative count of items', () => {
    //   navigateToCheckout();
    //   selectCheckoutDropdowns();
    //   cy.get("button[data-testid='add-to-cart-button']").should('not.be.disabled');
    //   cy.get("input[id='field_count'").clear().type(-1);
    //   cy.get("button[data-testid='add-to-cart-button']").should('be.disabled');
    // }); 
  
});