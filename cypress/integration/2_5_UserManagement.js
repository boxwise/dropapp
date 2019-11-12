const deactivateTestUserName = "BrowserTestUser_DeactivateTest";

describe('User management', () => {
    beforeEach(function () {
        cy.loginAsAdmin();
        cy.visit('/?action=cms_users');
    });

    function getUserRow(name){
        return cy.get('tr').contains(name);
    }

    function checkUserCheckboxByName(name){
        getUserRow(name).parent().parent().parent().within(() => {
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
        return cy.get("a").contains("Deactivated");
    }

    function getActiveUsersTab(){
        return cy.get("a").contains("Active");
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
        getUserRow(deactivateTestUserName).should('exist');
        checkUserCheckboxByName(deactivateTestUserName);
        getReactivateButton().click();
        getConfirmActionButton().should('be.visible');
        getCancelAction().should('be.visible');
        getCancelAction().click();
        getConfirmActionButton().should('not.exist');
        getReactivateButton().click();
        getConfirmActionButton().click();
        getActiveUsersTab().click();
        getUserRow(deactivateTestUserName).should('exist');
    });
});