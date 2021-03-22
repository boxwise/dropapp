const deactivateTestUserName = "BrowserTestUser_DeactivateTest";
const USER_DEACTVATE_REQUEST = "do=delete"
const USER_DEACTVATE_RESPONSE = "Item deleted";
const USER_REACTVATE_REQUEST = "do=undelete";
const USER_REACTVATE_RESPONSE = "Item recovered";

describe('User management', () => {
    beforeEach(function () {
        cy.setupAjaxActionHook();
        cy.loginAsAdmin();
        cy.visit('/?action=cms_users');
    });

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
        cy.getListDeleteButton().should('be.visible');
        cy.getListDeleteButton().click();
        cy.getConfirmActionButton().should('be.visible');
        getCancelAction().should('be.visible');
        getCancelAction().click();
        cy.getConfirmActionButton().should('not.exist');
        cy.getListDeleteButton().click();
        cy.getConfirmActionButton().click();
        cy.waitForAjaxAction(USER_DEACTVATE_REQUEST, USER_DEACTVATE_RESPONSE);
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
        cy.waitForAjaxAction(USER_REACTVATE_REQUEST, USER_REACTVATE_RESPONSE);
        getActiveUsersTab().click();
        cy.getRowWithText(deactivateTestUserName).should('exist');
    });
});