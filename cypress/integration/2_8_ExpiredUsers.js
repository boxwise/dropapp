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
const USER_DEACTVATE_REQUEST = "do=delete"
const USER_REACTVATE_REQUEST = "do=undelete";

describe("2_8_ExpiredUsers_Test", () => {

    beforeEach(function () {
        cy.setupAjaxActionHook();
        cy.loginAsAdmin();
        cy.visit('/?action=cms_users_expired');
    });

    it("2_8 Check for list elements in Expired tab", () => {
        cy.checkForElementByTypeAndTestId("input", "select_all");
        cy.checkElementIsVisibleByText("a", EXPIRED_COORDINATOR_NAME);
        cy.checkElementIsVisibleByText("div", EXPIRED_COORDINATOR_EMAIL);
        cy.checkElementIsVisibleByText("div", EXPIRED_COORDINATOR_ROLE);
        cy.checkElementIsVisibleByText("div", EXPIRED_COORDINATOR_VALID_FROM);
        cy.checkElementIsVisibleByText("div", EXPIRED_COORDINATOR_VALID_TO);

        // check that admin can see roles Coordinator and User
        cy.checkElementIsVisibleByText("div", EXPIRED_COORDINATOR_ROLE);
        cy.checkElementIsVisibleByText("div", EXPIRED_USER_ROLE);

        // login as coodrinator; check that coordinator can see roles User but not Admin
        cy.visit('/?logout=1');
        cy.loginAsCoordinator();
        cy.visit('/?action=cms_users_expired');
        cy.checkElementIsVisibleByText("div", EXPIRED_USER_ROLE);
        cy.checkElementDoesNotExistByText("div", EXPIRED_ADMIN_ROLE);
    });

    it("2_8_1 Click on expired user, edit page should open", () => {
        cy.clickOnElementBySelectorAndText("a", EXPIRED_COORDINATOR_NAME);
        cy.checkForElementByTypeAndTestId("input", "user_name");
        cy.checkForElementByTypeAndTestId("input", "user_email");
        cy.checkForElementByTypeAndTestId("select", "user_group");
        cy.checkForElementByTypeAndTestId("input", "user_valid_from");
        cy.checkForElementByTypeAndTestId("input", "user_valid_to");
        cy.checkForElementByTypeAndTestId("div", "user_last_login");
        cy.checkForElementByTypeAndTestId("div", "user_created_data");
    });

    it("2_8_2 Tick box for expired user", () => {
        cy.checkGridCheckboxByText(EXPIRED_COORDINATOR_NAME);
        cy.checkForElementByTypeAndTestId("button", "list-delete-button");
        // extend date button should appear
    });

    it("2_8_3 Select all expired users", () => {
        cy.checkForElementByTypeAndTestId("input", "select_all");
        cy.clickOnFirstElementBySelector("input[data-testid = 'select_all']");
        cy.checkAllUsersSelected();
        cy.checkForElementByTypeAndTestId("button", "list-delete-button");
        // extend date button should appear
    });

    // it("2_8_4 Extend validity of expired user", () => {
    // });

    it("2_8_5 Deactivate expired user", () => {
        cy.checkGridCheckboxByText(EXPIRED_COORDINATOR_NAME);
        cy.clickOnElementByTypeAndTestId("button", "list-delete-button");
        cy.checkElementIsVisibleByText("h3", ARE_YOU_SURE_POPUP);
        cy.clickOnElementBySelectorAndText("div.popover-content a", DEACTIVATE_BUTTON);
        cy.waitForAjaxAction(USER_DEACTVATE_REQUEST,ITEM_DELETED);

        cy.visit('/?action=cms_users_deactivated');
        cy.checkElementIsVisibleByText("p", EXPIRED_COORDINATOR_NAME);
        cy.checkGridCheckboxByText(EXPIRED_COORDINATOR_NAME);
        cy.clickOnElementByTypeAndTestId("button", "reactivate-cms-user");
        cy.clickOnElementBySelectorAndText("a", OK_BUTTON);
        cy.waitForAjaxAction(USER_REACTVATE_REQUEST,ITEM_RECOVERED);

        // test cancel button
        cy.visit('/?action=cms_users_expired');
        cy.checkElementIsVisibleByText("a", EXPIRED_COORDINATOR_NAME);
        cy.checkGridCheckboxByText(EXPIRED_COORDINATOR_NAME);
        cy.clickOnElementByTypeAndTestId("button", "list-delete-button");
        cy.clickOnElementBySelectorAndText("a", CANCEL_BUTTON);
        cy.checkClassByTypeAndTestId("button", "list-delete-button", "open", false);
    });

});
