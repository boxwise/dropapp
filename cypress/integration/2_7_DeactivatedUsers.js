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

    function checkForElementByText(selector, text) {
        cy.get(selector).contains(text).should("be.visible");
    }

    function checkElementDoesNotExistByText(selector, text) {
        cy.get(selector).contains(text).should('not.exist');
    }

    function checkClassByTypeAndTestId(type, testId, _class, hasClass) {
        if (hasClass == true) {
            cy.get(type + "[data-testid = '" + testId + "']").should("have.class", _class);
        } else {
            cy.get(type + "[data-testid = '" + testId + "']").should("not.have.class", _class);
        }
    }

    function clickOnElement(selector) {
        cy.get(selector).first().click();
    }

    function clickOnElementByText(selector, text) {
        cy.get(selector).contains(text).click();
    }

    function checkForElementByTypeAndTestId(type, testId) {
        cy.get(type + "[data-testid = '" + testId + "']").should("be.visible");
    }

    function clickOnElementByTypeAndTestId(type, testId) {
        cy.get(type + "[data-testid = '" + testId + "']").click();
    }

    function getUserRow(name){
        return cy.get('tr').contains(name);
    }

    function checkUserCheckboxByName(name){
        getUserRow(name).parent().parent().parent().within(() => {
            cy.get("input[type='checkbox']").check();
        });
    }

    function checkAllUsersSelected() {
        cy.get('tbody tr').each(($tr) => {
            expect($tr).to.have.class('selected');
        });
    }

    it("2_7 Check for list elements in Deactivated tab", () => {
        checkForElementByTypeAndTestId("input", "select_all");
        checkForElementByText("p", DELETED_COORDINATOR_NAME);
        checkForElementByText("div", DELETED_COORDINATOR_EMAIL);
        checkForElementByText("div", DELETED_COORDINATOR_ROLE);
        checkForElementByText("div", DELETED_COORDINATOR_VALID_FROM);
        checkForElementByText("div", DELETED_COORDINATOR_VALID_TO);

        // check that admin can see roles Coordinator and User
        checkForElementByText("div", DELETED_COORDINATOR_ROLE);
        checkForElementByText("div", DELETED_USER_ROLE);

        // login as coodrinator; check that coordinator can see roles User but not Admin
        cy.visit('/?logout=1');
        cy.loginAsCoordinator();
        cy.visit('/?action=cms_users_deactivated');
        checkForElementByText("div", DELETED_USER_ROLE);
        checkElementDoesNotExistByText("div", DELETED_ADMIN_ROLE);
    });

    it("2_7_1 Check that clicking disabled user does not load user edit page", () => {
        clickOnElementByText("p", DELETED_COORDINATOR_NAME);
        cy.url().should('not.include', 'cms_users_edit');
        cy.url().should('include', 'cms_users_deactivated');
    });

    it("2_7_2 Tick box for deactivated user", () => {
        checkUserCheckboxByName(DELETED_COORDINATOR_NAME);
        checkForElementByTypeAndTestId("button", "reactivate-cms-user");
    });

    it("2_7_3 Select all deactivated users", () => {
        checkForElementByTypeAndTestId("input", "select_all");
        clickOnElement("input[data-testid = 'select_all']");
        checkAllUsersSelected();
        checkForElementByTypeAndTestId("button", "reactivate-cms-user");
    });

    it("2_7_4 Activate deactivated user", () => {
        checkUserCheckboxByName(DELETED_USER_NAME);
        clickOnElementByTypeAndTestId("button", "reactivate-cms-user");
        checkForElementByText("h3", ARE_YOU_SURE_POPUP);
        clickOnElementByText("a", OK_BUTTON);
        cy.waitForAjaxAction(ITEM_RECOVERED);
        
        checkForElementByText("span", ITEM_RECOVERED);
        checkElementDoesNotExistByText("p", DELETED_USER_NAME);
        
        // check that re-activated user appears in Active Users tab
        cy.visit('/?action=cms_users');
        checkForElementByText("a", DELETED_USER_NAME);

        checkUserCheckboxByName(DELETED_USER_NAME);
        clickOnElementByTypeAndTestId("button", "list-delete-button");
        clickOnElementByText("div.popover-content a", DEACTIVATE_BUTTON);
        cy.waitForAjaxAction(ITEM_DELETED);
       
        // test cancel button
        cy.visit('/?action=cms_users_deactivated');
        checkUserCheckboxByName(DELETED_USER_NAME);
        clickOnElementByTypeAndTestId("button", "reactivate-cms-user");
        checkClassByTypeAndTestId("button", "reactivate-cms-user", "open", true);
        clickOnElementByText("a", CANCEL_BUTTON);
        checkClassByTypeAndTestId("button", "reactivate-cms-user", "open", false);
    });

});
