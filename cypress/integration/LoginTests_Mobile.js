context('Login tests - Mobile', () => {
    let testAdmin;
    let testCoordinator;
    let testUser;
    let testNotActivatedUser;
    let testExpiredUser;
    let testDeletedUser;
    let testPwd;
    let testWrongPwd;
    let testOrg;
    let domain;
    
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

        cy.getDomain().then(($result) => {
          domain = $result.domain;
        });

        cy.getTestOrgName().then(($result) => {
          testOrg = $result.orgName;
        });
    });

    it('Login test (Admin)', () => {
      cy.LoginMobile(testAdmin, testPwd);
      cy.get("h2[data-testid='mobileHeader']").should('be.visible');
    });
  
    it('Login test (Coordinator)', () => {
      cy.LoginMobile(testCoordinator, testPwd);
      cy.get("h2[data-testid='mobileHeader']").should('be.visible');
    })

    it('Login test (User)', () => {
        cy.LoginMobile(testUser, testPwd);
        cy.get("h2[data-testid='mobileHeader']").should('be.visible');
    })

    it('Login with non-activated user', () => {
      cy.LoginMobile(testNotActivatedUser, "password");
      //cy.MobileNotificationWithTextIsVisible("This user account is not yet valid.");
      cy.MobileNotificationWithTextIsVisible("Wrong password");
    })

    it('Login with expired user', () => {
      cy.LoginMobile(testExpiredUser , "password");
      //cy.MobileNotificationWithTextIsVisible("This user account is expired.");
      cy.MobileNotificationWithTextIsVisible("Wrong password");
    })

    it('Login with deleted user', () => {
      cy.LoginMobile(testDeletedUser , testPwd);
      cy.MobileNotificationWithTextIsVisible("This email does not have an active account associated with it. Please ask your coordinator to create an account for you.");
      //cy.MobileNotificationWithTextIsVisible("Wrong password");
    })

    it('Login with wrong password', () => {
        cy.LoginMobile(testAdmin , testWrongPwd);
        cy.MobileNotificationWithTextIsVisible("Wrong password");
    })
    
    it('Forgot password form', () => {
      cy.visit(domain+'/mobile.php');
      cy.get("a[data-testid='forgotPassword']").click();
      cy.get("form[data-testid='resetForm']").should('be.visible');
    });

    it('Forgot password form - nonexistent user', () => {
      cy.visit(domain+'/mobile.php');
      cy.get("a[data-testid='forgotPassword']").click();
      cy.get("form[data-testid='resetForm']").should('be.visible');
      cy.get("input[data-testid='forgotPwdEmailField']").type("nonexistent@address.com");
      cy.get("input[data-testid='submitForgottenPwd']").click();
      cy.NotificationWithTextIsVisible("This email does not exist in our systems. Check your spelling and try again")
    });

    it('Forgot password form success confirmation', () => {
      cy.visit(domain+'/mobile.php');
      cy.get("a[data-testid='forgotPassword']").click();
      cy.get("form[data-testid='resetForm']").should('be.visible');
      cy.get("input[data-testid='forgotPwdEmailField']").type(testAdmin);
      cy.get("input[data-testid='submitForgottenPwd']").click();
      cy.NotificationWithTextIsVisible("Within a few minutes you will receive an e-mail with further instructions to reset your password.")
    });
});