const USER_GROUP_COORDINATOR = "Base TestBase - Coordinator";
const USER_GROUP_USER = "Base TestBase - Volunteer";
const BROWSER_TEST_USER_USER = "BrowserTestUser_User";
const NEW_USER_BUTTON_LABEL = "New user";
const SEND_LOGIN_DATA_BUTTON_LABEL = "Send login data";
const USER_EMAIL = "user@user.co";
const BROWSER_TEST_USER_PENDING = "BrowserTestUser_Pending";

describe("2_6_UserMenu_Test", () => {

    beforeEach(function () {
        cy.loginAsAdmin();
        cy.visit('/?action=cms_users');
    });

    it("2_6 Check for list elements", () => {
        cy.checkForElementByTypeAndTestId("a.active", "active_pending");
        cy.checkForElementByTypeAndTestId("a", "expired");
        cy.checkForElementByTypeAndTestId("a", "deactivated");
        cy.checkForElementByTypeAndTestId("input", "select_all");
        cy.checkElementIsVisibleByText("a", NEW_USER_BUTTON_LABEL);
        cy.checkElementIsVisibleByText("div", USER_EMAIL);

        // check that admin can see roles Coordinator and User
        cy.checkElementIsVisibleByText("td[class='list-level- list-column-usergroup'] div", USER_GROUP_COORDINATOR);
        cy.checkElementIsVisibleByText("td[class='list-level- list-column-usergroup'] div", USER_GROUP_USER);

        // login as coodrinator; check that coordinator can see roles User but not Admin
        cy.visit('/?logout=1');
        cy.loginAsCoordinator();
        cy.visit('/?action=cms_users');
        cy.checkElementIsVisibleByText("td[class='list-level- list-column-usergroup'] div", USER_GROUP_USER);
        cy.checkElementDoesNotExistByText("tr", USER_GROUP_COORDINATOR);
    });

    it("2_6_1 Check for list elements for single user edit page", () => {
        cy.clickOnElementBySelectorAndText("a", BROWSER_TEST_USER_USER);
        cy.checkForElementByTypeAndTestId("input", "user_name");
        cy.checkForElementByTypeAndTestId("input", "user_email");
        cy.checkForElementByTypeAndTestId("select", "user_group");
        cy.checkForElementByTypeAndTestId("input", "user_valid_from");
        cy.checkForElementByTypeAndTestId("input", "user_valid_to");
        cy.checkForElementByTypeAndTestId("div", "user_last_login");
        cy.checkForElementByTypeAndTestId("div", "user_created_data");
    });

    it("2_6_2 Tick box for active user", () => {
        cy.checkGridCheckboxByText(BROWSER_TEST_USER_USER);
        cy.checkForElementByTypeAndTestId("button", "list-delete-button");
        cy.checkElementIsVisibleByText("button", SEND_LOGIN_DATA_BUTTON_LABEL);
    });

    it("2_6_3 Tick box for pending user", () => {
        cy.checkGridCheckboxByText(BROWSER_TEST_USER_PENDING);
        cy.checkForElementByTypeAndTestId("button", "list-delete-button");
        cy.checkElementIsVisibleByText("button", SEND_LOGIN_DATA_BUTTON_LABEL);
    });

    it("2_6_4 Select all users", () => {
        cy.checkForElementByTypeAndTestId("input", "select_all");
        cy.clickOnFirstElementBySelector("input[data-testid = 'select_all']");
        cy.checkForElementByTypeAndTestId("button", "list-delete-button");
        cy.checkElementIsVisibleByText("button", SEND_LOGIN_DATA_BUTTON_LABEL);
        cy.checkAllUsersSelected();
    });

});
