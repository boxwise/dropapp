const USER_GROUP_COORDINATOR = "TestUserGroup_Coordinator";
const USER_GROUP_USER = "TestUserGroup_User";
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

    function checkUserCheckboxByName(name){
        cy.getRowWithName(name).parent().parent().parent().within(() => {
            cy.get("input[type='checkbox']").check();
        });
    }

    function checkAllUsersSelected() {
        cy.get('tbody tr').each(($tr) => {
            expect($tr).to.have.class('selected');
        });
    }
 
    it("2_6 Check for list elements", () => {
        checkForElementByTypeAndTestId("a.active", "active_pending");
        checkForElementByTypeAndTestId("a", "expired");
        checkForElementByTypeAndTestId("a", "deactivated");
        checkForElementByTypeAndTestId("input", "select_all");
        checkForElementByText("a", NEW_USER_BUTTON_LABEL);
        checkForElementByText("div", USER_EMAIL);

        // check that admin can see roles Coordinator and User
        checkForElementByText("td[class='list-level- list-column-usergroup'] div", USER_GROUP_COORDINATOR);
        checkForElementByText("td[class='list-level- list-column-usergroup'] div", USER_GROUP_USER);

        // login as coodrinator; check that coordinator can see roles User but not Admin
        cy.visit('/?logout=1');
        cy.loginAsCoordinator();
        cy.visit('/?action=cms_users');
        checkForElementByText("td[class='list-level- list-column-usergroup'] div", USER_GROUP_USER);
        checkElementDoesNotExistByText("tr", USER_GROUP_COORDINATOR);
    });

    it("2_6_1 Check for list elements for single user edit page", () => {
        clickOnElementByText("a", BROWSER_TEST_USER_USER);
        checkForElementByTypeAndTestId("input", "user_name");
        checkForElementByTypeAndTestId("input", "user_email");
        checkForElementByTypeAndTestId("select", "user_group");
        checkForElementByTypeAndTestId("input", "user_valid_from");
        checkForElementByTypeAndTestId("input", "user_valid_to");
        checkForElementByTypeAndTestId("div", "user_last_login");
        checkForElementByTypeAndTestId("div", "user_created_data");
    });

    it("2_6_2 Tick box for active user", () => {
        checkUserCheckboxByName(BROWSER_TEST_USER_USER);
        checkForElementByTypeAndTestId("button", "list-delete-button");
        checkForElementByText("button", SEND_LOGIN_DATA_BUTTON_LABEL);
    });

    it("2_6_3 Tick box for pending user", () => {
        checkUserCheckboxByName(BROWSER_TEST_USER_PENDING);
        checkForElementByTypeAndTestId("button", "list-delete-button");
        checkForElementByText("button", SEND_LOGIN_DATA_BUTTON_LABEL);
    });

    it("2_6_4 Select all users", () => {
        checkForElementByTypeAndTestId("input", "select_all");
        clickOnElement("input[data-testid = 'select_all']");
        checkForElementByTypeAndTestId("button", "list-delete-button");
        checkForElementByText("button", SEND_LOGIN_DATA_BUTTON_LABEL);
        checkAllUsersSelected();
    });

});
