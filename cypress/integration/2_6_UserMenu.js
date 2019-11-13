context("2_6_UserMenu_Test", () => {

    beforeEach(function () {
        cy.loginAsAdmin();
        cy.visit('/?action=cms_users');
    });

    function CheckForElement(selector) {
        cy.get(selector).should("be.visible");
    }

    function CheckElementNotEmpty(selector) {
        cy.get(selector).should("not.be.empty");
    }

    function CheckForElementByText(selector, text) {
        cy.get(selector).should("be.visible").contains(text);
    }

    function ClickOnElement(selector) {
        cy.get(selector).first().click();
    }

    function CheckForElementByTypeAndTestId(type, testId) {
        cy.get(type + "[data-testid = '" + testId + "']").should("be.visible");
    }

    it("2_6 Check for list elements", () => {
        CheckForElementByTypeAndTestId("a.active", "active_pending");
        CheckForElementByTypeAndTestId("a", "expired");
        CheckForElementByTypeAndTestId("a", "deactivated");
        CheckForElementByTypeAndTestId("input", "select_all");
        CheckForElement("a[href='?action=cms_users_edit&origin=cms_users']");
        CheckElementNotEmpty("td[class='list-level- list-column-email'] div[class='td-content']");
        // test at both admin and coordinator levels
    });

    it("2_6_1 Check for list elements for single user edit page", () => {
        ClickOnElement("td[class='controls-front list-level- list-column-naam'] a[class='td-content-field']");
        CheckForElement("div[id='div_naam']");
        CheckForElement("div[id='div_email']");
        CheckForElement("div[id='div_cms_usergroups_id']");
        CheckForElement("div[id='div_valid_firstday']");
        CheckForElement("div[id='div_valid_lastday']");
        CheckForElement("div[id='div_lastlogin']");
        CheckForElement("div[class='created light small']");
    });

    it("2_6_2 Tick box for active user", () => {
        ClickOnElement("input[class='item-select']");
        CheckForElementByTypeAndTestId("button", "list-delete-button")
        CheckForElementByText("button", "Send login data");
    });

});
