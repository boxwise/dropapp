context("5_2_Add_Beneficiary_Test",()=>
{
    let Test_Organ = "TestOrganisation";
    let Test_user = "some.admin@boxwise.co";
    let Test_passwd = "admin";
    let Test_familyhead = "asdf";
    let Test_firstname = "Thomas";
    let Test_lastname = "Muller";
    let Test_case_id  = "IO";
    let Test_gender = "Female";


    beforeEach(function() {
        cy.Login(Test_user,Test_passwd);
        cy.get("a[data-testid='organisationsDropdown']").click();
        cy.get("li[data-testid='organisationOption'] a")
        .invoke('text').get('a').contains(Test_Organ).click();
        cy.get("a[class=menu_people]").last().click();
    })
    it("5_2 Load entry form",() => {
        cy.get("a[class=menu_people_add]").last().click();
        //check all the forms 
        cy.get("span[id='select2-chosen-1']").should("be.visible")
        cy.get("input[id = 'field_firstname']").should("be.visible")
        cy.get("input[id = 'field_lastname']").should("be.visible")
        cy.get("input[id='field_container']").should("be.visible")
        cy.get("span[id='select2-chosen-2']").should("be.visible")
        cy.get("input[id='field_date_of_birth']").should("be.visible")
        cy.get("input[id='s2id_autogen3']").should("be.visible")
        cy.get("textarea[id='field_comments']").should("be.visible")
        cy.get("input[id='field_volunteer']").should("be.visible")
        cy.get("input[id='field_notregistered']").should("be.visible")
        cy.get("a[id='tabid_bicycle']").should("be.visible")
        cy.get("a[id='tabid_signature']").should("be.visible")
        cy.get("button").contains("Save and close").should("be.visible")
        cy.get("button").contains("Save and new").should("be.visible")
        cy.get("a").contains("Cancel").should("be.visible")
    })
    it("Fill form, Save and close",() => {
        cy.get("a[class=menu_people_add]").last().click();
        //check all the forms 
        //cy.get("span[id='select2-chosen-1']").click();
        //cy.get("input[id=s2id_autogen1_search").type(Test_familyhead);
        cy.get("input[id = 'field_firstname']").type(Test_firstname);
        cy.get("input[id = 'field_lastname']").type(Test_lastname);
        cy.get("input[id='field_container']").type(Test_case_id);
        cy.get("span[id='select2-chosen-2']").click()
        cy.get("input[id='s2id_autogen2_search']").click().type(Test_gender);
        cy.get("div[class='select2-result-label']").first().click();
        cy.get("button").contains("Save and close").click();

    })
    it("Prevent emtpy submit",() => {
        cy.get("a[class=menu_people_add]").last().click();
        //check all the forms 
        //cy.get("span[id='select2-chosen-1']").click();
        //cy.get("input[id=s2id_autogen1_search").type(Test_familyhead);
        cy.get("button").contains("Save and close").click();
        cy.get("div[id='qtip-0-content']").should("be.visible");
        cy.get("div[id='qtip-1-content']").should("be.visible");

    })
    it("Prevent emtpy submit from signature-tab",() => {
        cy.get("a[class=menu_people_add]").last().click();
        cy.get("a[id='tabid_signature']").click();
        //check all the forms 
        //cy.get("span[id='select2-chosen-1']").click();
        //cy.get("input[id=s2id_autogen1_search").type(Test_familyhead);
        cy.get("button").contains("Save and close").click();
        //cy.get("div[id='qtip-0-content']").should("be.visible");
        //cy.get("div[id='qtip-1-content']").should("be.visible");

    })
    */
    it("5_2_4 Fill, Save and new",() => {
        cy.get("a[class=menu_people_add]").last().click();
        //check all the forms 
        cy.get("input[id = 'field_firstname']").type(Test_firstname);
        cy.get("input[id = 'field_lastname']").type(Test_lastname);
        cy.get("input[id='field_container']").type(Test_case_id);
        cy.get("span[id='select2-chosen-2']").click()
        cy.get("input[id='s2id_autogen2_search']").click().type(Test_gender);
        cy.get("div[class='select2-result-label']").first().click();
        cy.get("button").contains("Save and new").click();
        //back to blank page
        cy.wait(500);
        //cy.get("span[id='select2-chosen-1']").should("be.visible");
        cy.get("span[id='select2-chosen-1']").first().click();
        cy.get("input[id='s2id_autogen1_search']").click().type(Test_case_id +" "+ Test_firstname);
        cy.get("div[class='select2-result-label']").contains(Test_case_id + " "+ Test_firstname+ " "+ Test_lastname).should("be.visible")
        //first().click();
        cy.get("input[id='field_container']").should("be.empty")
        //cy.get("span[id='select2-chosen-2']").should("be.undefined")
        cy.get("input[id='field_date_of_birth']").should("be.empty")
        //cy.get("span[id='select2-chosen-2']").contains("Please select").should('be.visible')
        cy.get("input[id='s2id_autogen3']").should("be.empty")
        cy.get("textarea[id='field_comments']").should("be.empty")
        cy.get("input[id='field_volunteer']").should("be.empty")
        cy.get("input[id='field_notregistered']").should("be.empty")
    })
})
