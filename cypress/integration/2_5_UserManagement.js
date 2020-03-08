const deactivateTestUserName = "BrowserTestUser_DeactivateTest";

describe('User management', () => {
    beforeEach(function () {
        cy.loginAsAdmin();
        cy.visit('/?action=cms_users');
    });

    function getDeactivateButton(){
        return cy.get("button[data-testid='list-delete-button']");
    }

    function getCancelAction(){
        return cy.get("a[data-dismiss='confirmation']");
    }

    function getDeactivatedUsersTab(){
        return cy.get("a[data-testid='deactivated']");
    }

    function getActiveUsersTab(){
        return cy.get("a[data-testid='active_pending']")
    }

    function getReactivateButton(){
        return cy.get("button[data-operation='undelete']");
    }

    it('Deactivate & reactivate user', () => {
        cy.checkGridCheckboxByText(deactivateTestUserName);
        getDeactivateButton().should('be.visible');
        getDeactivateButton().click();
        cy.getConfirmActionButton().should('be.visible');
        getCancelAction().should('be.visible');
        getCancelAction().click();
        cy.getConfirmActionButton().should('not.exist');
        getDeactivateButton().click();
        cy.getConfirmActionButton().click();
        getDeactivatedUsersTab().click();
        getDeactivatedUsersTab().should('have.class', 'active')
        cy.getRowWithText(deactivateTestUserName).should('exist');
        cy.checkGridCheckboxByText(deactivateTestUserName);
        getReactivateButton().click();
        cy.getConfirmActionButton().should('be.visible');
        getCancelAction().should('be.visible');
        getCancelAction().click();
        cy.getConfirmActionButton().should('not.exist');
        getReactivateButton().click();
        cy.getConfirmActionButton().click();
        getActiveUsersTab().click();
        cy.getRowWithText(deactivateTestUserName).should('exist');
    });
});