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

    /*it('Login test (Admin)', () => {
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
    */

    /*it('Forgot password form', () => {
      cy.visit(domain+'/login.php');
      cy.get("a[data-testid='forgotPassword']").click();
      cy.get("form[data-testid='resetForm']").should('be.visible');
    });*/

    it('Forgot password form', () => {
      cy.Login(testAdmin, testPwd);
      cy.SelectOrganisationByName(testOrg);
      // /cy.get("form[data-testid='resetForm']").should('be.visible');
    });

});