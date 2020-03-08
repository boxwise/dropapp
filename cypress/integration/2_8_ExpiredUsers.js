const ARE_YOU_SURE_POPUP = "Are you sure?";
const CANCEL_BUTTON = "Cancel";
const OK_BUTTON = "OK";
const ITEM_RECOVERED = "Item recovered";
const DEACTIVATE_BUTTON = "Deactivate";
const ITEM_DELETED = "Item deleted";
const EXPIRED_COORDINATOR_NAME = "Expired Coordinator";
const EXPIRED_COORDINATOR_EMAIL = "expired_coordinator@expired.co";
const EXPIRED_USER_ROLE = "TestUserGroup_User";
const EXPIRED_COORDINATOR_ROLE = "TestUserGroup_Coordinator";
const EXPIRED_ADMIN_ROLE = "TestUserGroup_Admin";
const EXPIRED_COORDINATOR_VALID_FROM = "11 April 2017";
const EXPIRED_COORDINATOR_VALID_TO = "28 May 2017";

describe("2_8_ExpiredUsers_Test", () => {

    beforeEach(function () {
        cy.setupAjaxActionHook();
        cy.loginAsAdmin();
        cy.visit('/?action=cms_users_expired');
    });

    function checkForElementByText(selector, text) {
        cy.get(selector).contains(text).should("be.visible");
    }

    function checkElementDoesNotExistByText(selector, text) {
        cy.get(selector).contains(text).should('not.exist');
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

    function checkClassByTypeAndTestId(type, testId, _class, hasClass) {
        if (hasClass == true) {
            cy.get(type + "[data-testid = '" + testId + "']").should("have.class", _class);
        } else {
            cy.get(type + "[data-testid = '" + testId + "']").should("not.have.class", _class);
        }
    }

    function clickOnElementByTypeAndTestId(type, testId) {
        cy.get(type + "[data-testid = '" + testId + "']").click();
    }

    function checkAllUsersSelected() {
        cy.get('tbody tr').each(($tr) => {
            expect($tr).to.have.class('selected');
        });
    }
 
    it("2_8 Check for list elements in Expired tab", () => {
        checkForElementByTypeAndTestId("input", "select_all");
        checkForElementByText("a", EXPIRED_COORDINATOR_NAME);
        checkForElementByText("div", EXPIRED_COORDINATOR_EMAIL);
        checkForElementByText("div", EXPIRED_COORDINATOR_ROLE);
        checkForElementByText("div", EXPIRED_COORDINATOR_VALID_FROM);
        checkForElementByText("div", EXPIRED_COORDINATOR_VALID_TO);

        // check that admin can see roles Coordinator and User
        checkForElementByText("div", EXPIRED_COORDINATOR_ROLE);
        checkForElementByText("div", EXPIRED_USER_ROLE);

        // login as coodrinator; check that coordinator can see roles User but not Admin
        cy.visit('/?logout=1');
        cy.loginAsCoordinator();
        cy.visit('/?action=cms_users_expired');
        checkForElementByText("div", EXPIRED_USER_ROLE);
        checkElementDoesNotExistByText("div", EXPIRED_ADMIN_ROLE);
    });

    it("2_8_1 Click on expired user, edit page should open", () => {
        clickOnElementByText("a", EXPIRED_COORDINATOR_NAME);
        checkForElementByTypeAndTestId("input", "user_name");
        checkForElementByTypeAndTestId("input", "user_email");
        checkForElementByTypeAndTestId("select", "user_group");
        checkForElementByTypeAndTestId("input", "user_valid_from");
        checkForElementByTypeAndTestId("input", "user_valid_to");
        checkForElementByTypeAndTestId("div", "user_last_login");
        checkForElementByTypeAndTestId("div", "user_created_data");
    });

    it("2_8_2 Tick box for expired user", () => {
        cy.checkGridCheckboxByText(EXPIRED_COORDINATOR_NAME);
        checkForElementByTypeAndTestId("button", "list-delete-button");
        // extend date button should appear
    });

    it("2_8_3 Select all expired users", () => {
        checkForElementByTypeAndTestId("input", "select_all");
        clickOnElement("input[data-testid = 'select_all']");
        checkAllUsersSelected();
        checkForElementByTypeAndTestId("button", "list-delete-button");
        // extend date button should appear
    });

    // it("2_8_4 Extend validity of expired user", () => {
    // });

    it("2_8_5 Deactivate expired user", () => {
        cy.checkGridCheckboxByText(EXPIRED_COORDINATOR_NAME);
        clickOnElementByTypeAndTestId("button", "list-delete-button");
        checkForElementByText("h3", ARE_YOU_SURE_POPUP);
        clickOnElementByText("div.popover-content a", DEACTIVATE_BUTTON);
        cy.waitForAjaxAction(ITEM_DELETED);

        cy.visit('/?action=cms_users_deactivated');
        checkForElementByText("p", EXPIRED_COORDINATOR_NAME);
        cy.checkGridCheckboxByText(EXPIRED_COORDINATOR_NAME);
        clickOnElementByTypeAndTestId("button", "reactivate-cms-user");
        clickOnElementByText("a", OK_BUTTON);
        cy.waitForAjaxAction(ITEM_RECOVERED);

        // test cancel button
        cy.visit('/?action=cms_users_expired');
        checkForElementByText("a", EXPIRED_COORDINATOR_NAME);
        cy.checkGridCheckboxByText(EXPIRED_COORDINATOR_NAME);
        clickOnElementByTypeAndTestId("button", "list-delete-button");
        clickOnElementByText("a", CANCEL_BUTTON);
        checkClassByTypeAndTestId("button", "list-delete-button", "open", false);
    });

});
