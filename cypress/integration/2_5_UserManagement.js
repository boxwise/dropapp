const deactivateTestUserName = "BrowserTestUser_DeactivateTest";

describe('User management', () => {
    beforeEach(function () {
        cy.loginAsAdmin();
        cy.visit('/?action=cms_users');
    });

    function checkUserCheckboxByName(name){
        cy.getRowWithName(name).parent().parent().parent().within(() => {
            cy.get("input[type='checkbox']").check();
        });
    }

    function getDeactivateButton(){
        return cy.get("button[data-testid='list-delete-button']");
    }

    function getConfirmActionButton(){
        return cy.get("a[data-apply='confirmation']");
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
        checkUserCheckboxByName(deactivateTestUserName);
        getDeactivateButton().should('be.visible');
        getDeactivateButton().click();
        getConfirmActionButton().should('be.visible');
        getCancelAction().should('be.visible');
        getCancelAction().click();
        getConfirmActionButton().should('not.exist');
        getDeactivateButton().click();
        getConfirmActionButton().click();
        getDeactivatedUsersTab().click();
        getDeactivatedUsersTab().should('have.class', 'active')
        cy.getRowWithName(deactivateTestUserName).should('exist');
        checkUserCheckboxByName(deactivateTestUserName);
        getReactivateButton().click();
        getConfirmActionButton().should('be.visible');
        getCancelAction().should('be.visible');
        getCancelAction().click();
        getConfirmActionButton().should('not.exist');
        getReactivateButton().click();
        getConfirmActionButton().click();
        getActiveUsersTab().click();
        cy.getRowWithName(deactivateTestUserName).should('exist');
    });
});