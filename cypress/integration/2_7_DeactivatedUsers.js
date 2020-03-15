const DELETED_COORDINATOR_NAME = "Deleted Coordinator";
const DELETED_COORDINATOR_EMAIL = "deleted_coordinator@deleted.co";
const DELETED_COORDINATOR_ROLE = "TestUserGroup_Coordinator";
const DELETED_COORDINATOR_VALID_FROM = "26 May 2018";
const DELETED_COORDINATOR_VALID_TO = "26 May 2019";
const DELETED_USER_ROLE = "TestUserGroup_User";
const DELETED_ADMIN_ROLE = "TestUserGroup_Admin";
const DELETED_USER_EMAIL = "deleted@deleted.co";
const DELETED_USER_NAME = "Deleted User";
const ARE_YOU_SURE_POPUP = "Are you sure?";
const CANCEL_BUTTON = "Cancel";
const OK_BUTTON = "OK";
const ITEM_RECOVERED = "Item recovered";
const ITEM_DELETED = "Item deleted";
const DEACTIVATE_BUTTON = "Deactivate";

describe("2_7_DeactivatedUsers_Test", () => {

    beforeEach(function () {
        cy.setupAjaxActionHook();
        cy.loginAsAdmin();
        cy.visit('/?action=cms_users_deactivated');
    });

    function clickOnElement(selector) {
        cy.get(selector).first().click();
    }

    it("2_7 Check for list elements in Deactivated tab", () => {
        cy.checkForElementByTypeAndTestId("input", "select_all");
        cy.checkElementIsVisibleByText("p", DELETED_COORDINATOR_NAME);
        cy.checkElementIsVisibleByText("div", DELETED_COORDINATOR_EMAIL);
        cy.checkElementIsVisibleByText("div", DELETED_COORDINATOR_ROLE);
        cy.checkElementIsVisibleByText("div", DELETED_COORDINATOR_VALID_FROM);
        cy.checkElementIsVisibleByText("div", DELETED_COORDINATOR_VALID_TO);

        // check that admin can see roles Coordinator and User
        cy.checkElementIsVisibleByText("div", DELETED_COORDINATOR_ROLE);
        cy.checkElementIsVisibleByText("div", DELETED_USER_ROLE);

        // login as coodrinator; check that coordinator can see roles User but not Admin
        cy.visit('/?logout=1');
        cy.loginAsCoordinator();
        cy.visit('/?action=cms_users_deactivated');
        cy.checkElementIsVisibleByText("div", DELETED_USER_ROLE);
        cy.checkElementDoesNotExistByText("div", DELETED_ADMIN_ROLE);
    });

    it("2_7_1 Check that clicking disabled user does not load user edit page", () => {
        cy.clickOnElementBySelectorAndText("p", DELETED_COORDINATOR_NAME);
        cy.url().should('not.include', 'cms_users_edit');
        cy.url().should('include', 'cms_users_deactivated');
    });

    it("2_7_2 Tick box for deactivated user", () => {
        cy.checkGridCheckboxByText(DELETED_COORDINATOR_NAME);
        cy.checkForElementByTypeAndTestId("button", "reactivate-cms-user");
    });

    it("2_7_3 Select all deactivated users", () => {
        cy.checkForElementByTypeAndTestId("input", "select_all");
        clickOnElement("input[data-testid = 'select_all']");
        cy.checkAllUsersSelected();
        cy.checkForElementByTypeAndTestId("button", "reactivate-cms-user");
    });

    it("2_7_4 Activate deactivated user", () => {
        cy.checkGridCheckboxByText(DELETED_USER_NAME);
        cy.clickOnElementByTypeAndTestId("button", "reactivate-cms-user");
        cy.checkElementIsVisibleByText("h3", ARE_YOU_SURE_POPUP);
        cy.clickOnElementBySelectorAndText("a", OK_BUTTON);
        cy.waitForAjaxAction(ITEM_RECOVERED);
        
        cy.checkElementIsVisibleByText("span", ITEM_RECOVERED);
        cy.checkElementDoesNotExistByText("p", DELETED_USER_NAME);
        
        // check that re-activated user appears in Active Users tab
        cy.visit('/?action=cms_users');
        cy.checkElementIsVisibleByText("a", DELETED_USER_NAME);

        cy.checkGridCheckboxByText(DELETED_USER_NAME);
        cy.clickOnElementByTypeAndTestId("button", "list-delete-button");
        cy.clickOnElementBySelectorAndText("div.popover-content a", DEACTIVATE_BUTTON);
        cy.waitForAjaxAction(ITEM_DELETED);
       
        // test cancel button
        cy.visit('/?action=cms_users_deactivated');
        cy.checkGridCheckboxByText(DELETED_USER_NAME);
        cy.clickOnElementByTypeAndTestId("button", "reactivate-cms-user");
        cy.checkClassByTypeAndTestId("button", "reactivate-cms-user", "open", true);
        cy.clickOnElementBySelectorAndText("a", CANCEL_BUTTON);
        cy.checkClassByTypeAndTestId("button", "reactivate-cms-user", "open", false);
    });

});
