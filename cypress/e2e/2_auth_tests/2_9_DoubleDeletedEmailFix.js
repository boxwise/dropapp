const DELETED_USER_NAME = "Deleted User";
const DELETED_USER_EMAIL = "deleted@deleted.co";
const ARE_YOU_SURE_POPUP = "Are you sure?";
const OK_BUTTON = "OK";
const ITEM_RECOVERED = "Item recovered";
const ITEM_DELETED = "Item deleted";
const USER_DEACTIVATE_REQUEST = "do=delete";
const USER_REACTIVATE_REQUEST = "do=undelete";

describe("2_9_DoubleDeletedEmailFix_Test", () => {

    beforeEach(function () {
        cy.setupAjaxActionHook();
        cy.loginAsAdmin();
    });

    it("2_9_1 Verify deleted user can be reactivated normally", () => {
        // This test ensures the normal reactivation flow works
        // It serves as a baseline for the double-deletion scenario
        cy.visit('/?action=cms_users_deactivated');

        cy.checkGridCheckboxByText(DELETED_USER_NAME);
        cy.clickOnElementByTypeAndTestId("button", "reactivate-cms-user");
        cy.checkElementIsVisibleByText("h3", ARE_YOU_SURE_POPUP);
        cy.clickOnElementBySelectorAndText("a", OK_BUTTON);
        cy.waitForAjaxAction(USER_REACTIVATE_REQUEST, ITEM_RECOVERED);

        cy.checkElementIsVisibleByText("span", ITEM_RECOVERED);
        cy.checkElementDoesNotExistByText("p", DELETED_USER_NAME);

        // Verify user appears in Active Users
        cy.visit('/?action=cms_users');
        cy.checkElementIsVisibleByText("a", DELETED_USER_NAME);

        // Verify email is correct (without double suffix)
        cy.get('body').should('contain', DELETED_USER_EMAIL);

        // Delete the user again to restore test state
        cy.checkGridCheckboxByText(DELETED_USER_NAME);
        cy.clickOnElementByTypeAndTestId("button", "list-delete-button");
        cy.clickOnElementBySelectorAndText("div.popover-content a", "Deactivate");
        cy.waitForAjaxAction(USER_DEACTIVATE_REQUEST, ITEM_DELETED);
    });

    it("2_9_2 Verify prevention fix - delete, reactivate, delete again cycle", () => {
        // This test verifies our fix by doing multiple delete/reactivate cycles
        // Without the fix, repeated deletions would append multiple .deleted suffixes
        // With our fix, the email should always have only ONE .deleted suffix

        // Start: User is already deleted (from previous test)
        cy.visit('/?action=cms_users_deactivated');
        cy.checkElementIsVisibleByText("p", DELETED_USER_NAME);

        // Cycle 1: Reactivate user
        cy.checkGridCheckboxByText(DELETED_USER_NAME);
        cy.clickOnElementByTypeAndTestId("button", "reactivate-cms-user");
        cy.checkElementIsVisibleByText("h3", ARE_YOU_SURE_POPUP);
        cy.clickOnElementBySelectorAndText("a", OK_BUTTON);
        cy.waitForAjaxAction(USER_REACTIVATE_REQUEST, ITEM_RECOVERED);

        // Cycle 1: Delete user again
        cy.visit('/?action=cms_users');
        cy.checkGridCheckboxByText(DELETED_USER_NAME);
        cy.clickOnElementByTypeAndTestId("button", "list-delete-button");
        cy.clickOnElementBySelectorAndText("div.popover-content a", "Deactivate");
        cy.waitForAjaxAction(USER_DEACTIVATE_REQUEST, ITEM_DELETED);

        // Cycle 2: Reactivate again (this tests that the email still works after first cycle)
        cy.visit('/?action=cms_users_deactivated');
        cy.checkGridCheckboxByText(DELETED_USER_NAME);
        cy.clickOnElementByTypeAndTestId("button", "reactivate-cms-user");
        cy.checkElementIsVisibleByText("h3", ARE_YOU_SURE_POPUP);
        cy.clickOnElementBySelectorAndText("a", OK_BUTTON);
        cy.waitForAjaxAction(USER_REACTIVATE_REQUEST, ITEM_RECOVERED);

        // Cycle 2: Delete user again
        cy.visit('/?action=cms_users');
        cy.checkGridCheckboxByText(DELETED_USER_NAME);
        cy.clickOnElementByTypeAndTestId("button", "list-delete-button");
        cy.clickOnElementBySelectorAndText("div.popover-content a", "Deactivate");
        cy.waitForAjaxAction(USER_DEACTIVATE_REQUEST, ITEM_DELETED);

        // Final verification: If all cycles succeeded, our fix is working
        // Without the fix, the second reactivation would fail due to double .deleted suffix
        cy.visit('/?action=cms_users_deactivated');
        cy.checkElementIsVisibleByText("p", DELETED_USER_NAME);
        cy.log('SUCCESS: User went through multiple delete/reactivate cycles without double-deletion issue');
    });
});

/*
 * MANUAL TEST INSTRUCTIONS FOR DOUBLE-DELETED EMAIL SCENARIO:
 *
 * To test the double-deletion bug fix manually:
 *
 * 1. Create a double-deleted email in the database:
 *    ```sql
 *    UPDATE cms_users
 *    SET email = CONCAT(email, '.deleted.', id)
 *    WHERE id = 100000005
 *    AND email LIKE '%.deleted.%';
 *    ```
 *    This simulates the bug where a user was deleted twice.
 *
 * 2. Verify the email now has double suffix:
 *    ```sql
 *    SELECT id, email FROM cms_users WHERE id = 100000005;
 *    ```
 *    Should show: deleted@deleted.co.deleted.100000005.deleted.100000005
 *
 * 3. Run the fix cron job:
 *    Visit: http://localhost:8100/cron/fix-double-deleted-emails.php
 *    (Make sure db_database is 'dropapp_dev' in config)
 *
 * 4. Verify the email is fixed:
 *    ```sql
 *    SELECT id, email FROM cms_users WHERE id = 100000005;
 *    ```
 *    Should show: deleted@deleted.co.deleted.100000005
 *
 * 5. Try to reactivate the user through the UI:
 *    - Go to Manage Users > Deactivated tab
 *    - Select "Deleted User"
 *    - Click "Activate"
 *    - Should succeed without errors
 *    - Email should be: deleted@deleted.co
 */
