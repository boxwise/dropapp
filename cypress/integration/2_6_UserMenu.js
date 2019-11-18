const USER_GROUP_COORDINATOR = "TestUserGroup_Coordinator";
const USER_GROUP_USER = "TestUserGroup_User";
const BROWSER_TEST_USER_USER = "BrowserTestUser_User";
const NEW_USER_BUTTON_LABEL = "New user";
const SEND_LOGIN_DATA_BUTTON_LABEL = "Send login data";
const USER_EMAIL = "user@user.co";

describe("2_6_UserMenu_Test", () => {

    beforeEach(function () {
        cy.loginAsAdmin();
        cy.visit('/?action=cms_users');
    });

    function CheckForElementByText(selector, text) {
        cy.get(selector).contains(text).should("be.visible");
    }

    function CheckElementDoesNotExistByText(selector, text) {
        cy.get(selector).contains(text).should('not.exist');
    }

    function ClickOnElement(selector) {
        cy.get(selector).first().click();
    }

    function ClickOnElementByText(selector, text) {
        cy.get(selector).contains(text).click();
    }

    function CheckForElementByTypeAndTestId(type, testId) {
        cy.get(type + "[data-testid = '" + testId + "']").should("be.visible");
    }

    it("2_6 Check for list elements", () => {
        CheckForElementByTypeAndTestId("a.active", "active_pending");
        CheckForElementByTypeAndTestId("a", "expired");
        CheckForElementByTypeAndTestId("a", "deactivated");
        CheckForElementByTypeAndTestId("input", "select_all");
        CheckForElementByText("a", NEW_USER_BUTTON_LABEL);
        CheckForElementByText("div", USER_EMAIL);

        // check that admin can see roles Coordinator and User
        CheckForElementByText("td[class='list-level- list-column-usergroup'] div", USER_GROUP_COORDINATOR);
        CheckForElementByText("td[class='list-level- list-column-usergroup'] div", USER_GROUP_USER);

        // login as coodrinator; check that coordinator can see roles User but not Admin
        cy.visit('/?logout=1');
        cy.loginAsCoordinator();
        cy.visit('/?action=cms_users');
        CheckForElementByText("td[class='list-level- list-column-usergroup'] div", USER_GROUP_USER);
        CheckElementDoesNotExistByText("tr", USER_GROUP_COORDINATOR);
    });

    it("2_6_1 Check for list elements for single user edit page", () => {
        ClickOnElementByText("a", BROWSER_TEST_USER_USER);
        CheckForElementByTypeAndTestId("input", "user_name");
        CheckForElementByTypeAndTestId("input", "user_email");
        CheckForElementByTypeAndTestId("select", "user_group");
        CheckForElementByTypeAndTestId("input", "user_valid_from");
        CheckForElementByTypeAndTestId("input", "user_valid_to");
        CheckForElementByTypeAndTestId("div", "user_last_login");
        CheckForElementByTypeAndTestId("div", "user_created_data");
    });

    it("2_6_2 Tick box for active user", () => {
        ClickOnElement("input[class='item-select']");
        CheckForElementByTypeAndTestId("button", "list-delete-button")
        CheckForElementByText("button", SEND_LOGIN_DATA_BUTTON_LABEL);
    });

});
