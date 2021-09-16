context("User creation", () => {
    let Testname = "paul";
    let Testgroup = "TestUserGroup_User";
    let Testaddress = "pauli@pauli.co";
    let Alt_org_name = "Coordinator Bob";
    let Alt_org_mail = "coordinator@coordinator.co";

Cypress.config("defaultCommandTimeout",200000)

    function DeleteTestUser(address) {
        cy.testdbdelete('cms_users', [], [address]);
    }

    function FillForm(name, address, group) {
        cy.get("input[data-testid='user_name']").type(name);
        cy.get("input[data-testid='user_email']").type(address);
        cy.get("input[data-testid='user_email2']").type(address);
        cy.selectOptionByText("cms_usergroups_id", group);
    }

    function CheckRequiredFields(){
        cy.checkQtip('qtip-1-content');
        cy.checkQtip('qtip-2-content');
        cy.checkQtip('qtip-3-content');
    }

    beforeEach(function () {
        cy.loginAsAdmin();
        cy.visit('/?action=cms_users_edit&origin=cms_users');
    })
    
    it("2_4_0_A Check empty form + empty submit",() => {
        cy.get("input[data-testid='user_name']").should("be.visible");
        cy.get("input[data-testid='user_email']").should("be.visible");
        cy.get("input[data-testid='user_email2']").should("be.visible");
        cy.CheckDropDownEmpty("cms_usergroups_id");
        cy.get("input[data-testid='user_valid_from']").should("be.visible");
        cy.get("input[data-testid='user_valid_to']").should("be.visible");
        cy.getButtonWithText("Save and close").should('be.visible');
        cy.get("a").contains("Cancel").should('be.visible');
        cy.getButtonWithText("Save and close").click();
        CheckRequiredFields();
    })
    
    it("2_4_0_B Create New user", () => {
        DeleteTestUser(Testaddress);
        FillForm(Testname, Testaddress, Testgroup);
        cy.getButtonWithText("Save and close").click();
        cy.url().should('not.include', 'edit');
        cy.get("tr").contains(Testaddress).should("be.visible");
        
    })
    
    it("2_4_1 Create New User without expiry date", () => {
        DeleteTestUser(Testaddress);
        FillForm(Testname, Testaddress, Testgroup);
        cy.getButtonWithText("Save and close").click();
        cy.get("tr").contains(Testaddress).should("be.visible");
    })

    it("2_4_3 Create New User with current date", () => {
        DeleteTestUser(Testaddress);
        FillForm(Testname,Testaddress,Testgroup);
        let today = new Date();
        let current_date = today.getDate() + "-" + today.getMonth() + "-"+today.getFullYear();
        cy.get("input[data-testid='user_valid_to']").type(current_date);
        cy.getButtonWithText("Save and close").click();
        cy.get("tr").contains(Testaddress).should("be.visible");
    })
    
    it("2_4_4 Create User already existing in another organisation", () => {
        FillForm(Alt_org_name,Alt_org_mail,Testgroup);
        cy.getButtonWithText("Save and close").click();
        cy.notyTextNotificationWithTextIsVisible("This email already exists");
    })
    
    it("2_4_5 Create already existing user same organisation", () => {
        DeleteTestUser(Testaddress);
        FillForm(Testname,Testaddress,Testgroup);
        cy.getButtonWithText("Save and close").click();
        cy.get("a[data-testid='NewItem']").click();
        FillForm(Testname,Testaddress,Testgroup);
        cy.getButtonWithText("Save and close").click();
        cy.notyTextNotificationWithTextIsVisible("This email already exists in your organisation");
    })
})
