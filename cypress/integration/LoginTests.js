context('Login tests', () => {
    let testAdmin;
    let testCoordinator;
    let testUser;
    let testPwd;
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

        cy.getDomain().then(($result) => {
          domain = $result.domain;
        });

        cy.getTestOrgName().then(($result) => {
          testOrg = $result.orgName;
        });
    });

    beforeEach(() => {
      //cy.visit('http://localhost:8100/login.php')
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
    

    it('Forgot password form', () => {
      cy.visit(domain+'/login.php');
      cy.get("a[data-testid='forgotPassword']").click();
      cy.get("form[data-testid='resetForm']").should('be.visible');
    });

    it('Forgot password form failure confirmation', () => {
      cy.visit(domain+'/login.php');
      cy.get("a[data-testid='forgotPassword']").click();
      cy.get("form[data-testid='resetForm']").should('be.visible');
      cy.get("input[data-testid='forgotPwdEmailField']").type("nonexistent@address.com");
      cy.get("input[data-testid='submitForgottenPwd']").click();
      cy.NotificationWithTextIsVisible("This email doesn't exist in our systems. Check your spelling and try again")
    });

    it('Forgot password form success confirmation', () => {
      cy.visit(domain+'/login.php');
      cy.get("a[data-testid='forgotPassword']").click();
      cy.get("form[data-testid='resetForm']").should('be.visible');
      cy.get("input[data-testid='forgotPwdEmailField']").type(testAdmin);
      cy.get("input[data-testid='submitForgottenPwd']").click();
      cy.NotificationWithTextIsVisible("Within a few minutes you will receive an e-mail with further instructions to reset your password.")
    });

});