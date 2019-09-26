context("User creation text", () => {
    let Testname = "paul";
    let Testgroup = "TestUserGroup_User";
    let Testaddress = "paul@paul.co";
    let Alt_org_name = "Volunteer Hans";
    let Alt_org_mail = "hans@boxwise.co"

    function DeleteTestUser(address) {
        cy.get("tr").contains(address).parent("td").parent("tr").children().first().children("div").children("label").click()
        cy.get("button[data-operation='delete']").click()
        cy.get("a[data-apply='confirmation']").click()
    }

    function FillForm(name,address,group){
        cy.get("input[data-testid='name_id']").type(name)
        cy.get("input[data-testid='email_id']").type(address)
        cy.selectOptionByText("cms_usergroups_id",group)

    }

    beforeEach(function() {
        cy.loginAsAdmin();
        cy.visit('/?action=cms_users_edit&origin=cms_users');
        
    })

    it("2_4_1 Create New User without axpiry date", () => {
        FillForm(Testname,Testaddress,Testgroup)
        cy.get("button").contains("Save and close").click()
        cy.get("tr").contains(Testaddress).should("be.visible")
        DeleteTestUser(Testaddress)
    })
    
    it("2_4_3 Create New User with current date", () => {
        FillForm(Testname,Testaddress,Testgroup)
        let today = new Date()
        let current_date = today.getDate() + "-" + today.getMonth() + "-"+today.getFullYear();
        cy.get("input[data-testid='valid_to_id']").type(current_date);
        cy.get("button").contains("Save and close").click()
        cy.get("ul[class='pagemenu list-unstyled']").children('li').last().click()
        DeleteTestUser(Testaddress)
        cy.get("a").contains("Deactivated").click()
        
    })
    it("2_4_4 Create User already existing in another organisation", () => {
        FillForm(Alt_org_name,Alt_org_mail,Testgroup)
        cy.get("button").contains("Save and close").click()
        cy.notificationWithTextIsVisible("This email already exists in another organisation")
    })
    it("2_4_5 Create already existing user same organisation", () => {
        FillForm(Testname,Testaddress,Testgroup)
        cy.get("button").contains("Save and close").click()
        cy.get("a[class = 'new-page item-add btn btn-sm btn-default'").click()
        FillForm(Testname,Testaddress,Testgroup)
        cy.get("button").contains("Save and close").click()
        cy.notificationWithTextIsVisible("This email already exists in your organisation")
        DeleteTestUser(Testaddress)
    })
})