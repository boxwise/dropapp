context('Login tests', () => {
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

        cy.getLoginNotifications().then(($result) => {
          incorrectLoginNotif = $result.incorrectLoginNotif;
          genericErrLoginNotif = $result.genericErrLoginNotif;
          successPwdChangeNotif = $result.successPwdChangeNotif;
        });

        cy.getDomain().then(($result) => {
          domain = $result.domain;
        });

        cy.getTestOrgName().then(($result) => {
          testOrg = $result.orgName;
        });
    });

    it('Login test (Admin)', () => {
      cy.Login(testAdmin, testPwd);
      cy.get("div[data-testid='dropapp-header']").should('be.visible');
    });
  
    it('Login test (Coordinator)', () => {
      cy.Login(testCoordinator, testPwd);
      cy.get("div[data-testid='dropapp-header']").should('be.visible');
    })

    it('Login test (User)', () => {
        cy.Login(testUser, testPwd);
        cy.get("div[data-testid='dropapp-header']").should('be.visible');
    })

    it('Login with non-activated user', () => {
      cy.Login(testNotActivatedUser, testPwd);
      cy.NotificationWithTextIsVisible(genericErrLoginNotif);
    })

    it('Login with expired user', () => {
      cy.Login(testExpiredUser , testPwd);
      cy.NotificationWithTextIsVisible(genericErrLoginNotif);
    })

    it('Login with deleted user', () => {
      cy.Login(testDeletedUser , testPwd);
      cy.NotificationWithTextIsVisible(genericErrLoginNotif);
    })

    it('Login with wrong password', () => {
      cy.Login(testAdmin , testWrongPwd);
      cy.NotificationWithTextIsVisible(incorrectLoginNotif);
  })
    
    it('Forgot password form', () => {
      cy.visit(domain+'/login.php');
      cy.get("a[data-testid='forgotPassword']").click();
      cy.get("form[data-testid='resetForm']").should('be.visible');
    });

    it('Forgot password form - nonexistent user', () => {
      cy.visit(domain+'/login.php');
      cy.get("a[data-testid='forgotPassword']").click();
      cy.get("form[data-testid='resetForm']").should('be.visible');
      cy.get("input[data-testid='forgotPwdEmailField']").type("nonexistent@address.com");
      cy.get("input[data-testid='submitForgottenPwd']").click();
      cy.NotificationWithTextIsVisible(genericErrLoginNotif)
    });

    it('Forgot password form success confirmation', () => {
      cy.visit(domain+'/login.php');
      cy.get("a[data-testid='forgotPassword']").click();
      cy.get("form[data-testid='resetForm']").should('be.visible');
      cy.get("input[data-testid='forgotPwdEmailField']").type(testAdmin);
      cy.get("input[data-testid='submitForgottenPwd']").click();
      cy.NotificationWithTextIsVisible(successPwdChangeNotif)
    });
});