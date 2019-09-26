context("5_2_Add_Beneficiary_Test",()=>
{
    let Test_firstname = "John";
    let Test_firstname2 = "Jim";
    let Test_lastname = "Smith";
    let Test_case_id  = "IO";

    
    beforeEach(function() {
        cy.loginAsVolunteer();
        cy.visit('/?action=people_edit&origin=people');
    })


    function CheckEmptyForm() {
    cy.get("span[id='select2-chosen-1']").contains('Please select').should("be.visible")
    cy.get("input[data-testid = 'firstname_id']").should("be.empty")
    cy.get("input[data-testid = 'lastname_id']").should("be.empty")
    cy.get("input[data-testid = 'container_id']").should("be.empty")
    cy.get("span[id='select2-chosen-2']").contains('Please select').should("be.visible")
    cy.get("input[data-testid='date_of_birth_id']").should("be.empty")
    cy.get("input[id='s2id_autogen3']").should("be.empty")
    cy.get("textarea[data-testid='comments_id']").should("be.empty")
    cy.get("input[data-testid='volunteer_id']").should("be.empty")
    cy.get("input[data-testid='registered_id']").should("be.empty")
    cy.get("a[id='tabid_bicycle']").should("be.visible")
    cy.get("a[id='tabid_signature']").should("be.visible")
    //Check buttons
    cy.get("button").contains("Save and close").should("be.visible")
    cy.get("button").contains("Save and new").should("be.visible")
    cy.get("a").contains("Cancel").should("be.visible")
    }

    function FillForm(firstname,lastname,case_id,gender)
    {cy.get("input[data-testid = 'firstname_id']").type(firstname);
    cy.get("input[data-testid = 'lastname_id']").type(lastname);
    if (case_id != ""){
        cy.get("input[data-testid='container_id']").type(case_id);
    }

    }
    /*
    it("5_2_1 Fill form, Save and close",() => {
        CheckEmptyForm();
        //check all the forms 
        FillForm(Test_firstname,Test_lastname,Test_case_id);
        cy.get("button").contains("Save and close").click();
        cy.notificationWithTextIsVisible(Test_firstname+" "+Test_lastname + " was added")
        //cy.get("span[class='noty_text']").contains(Test_firstname+" "+Test_lastname + " was added").should("be.visible");
        cy.get("div").contains(Test_case_id).should("be.visible");

    })
    
    it("5_2_2 Prevent emtpy submit",() => {

        cy.get("button").contains("Save and close").click();
        cy.get("div[id='qtip-0-content']").should("be.visible");
        cy.get("div[id='qtip-1-content']").should("be.visible");

    })
    */


    it("5_2_4 Save and new check if new person in familyhead-dropdown + check if empty",() => {
        //check all the forms 
        FillForm(Test_firstname,Test_lastname,Test_case_id);
        cy.get("button").contains("Save and new").click();
        cy.get("span[class='noty_text']").contains(Test_firstname+" "+Test_lastname + " was added").should("be.visible");
        cy.get("span[class='noty_text']").contains(Test_case_id).should("be.visible");

        // Check for the familyhead after adding it above
        cy.get("span[id='select2-chosen-1']").click();
        cy.get("input[id='s2id_autogen1_search']").click().type(Test_case_id +" "+ Test_firstname);
        cy.get("div[class='select2-result-label']").contains(Test_case_id + " "+ Test_firstname+ " "+ Test_lastname).click();
        FillForm(Test_firstname2,Test_lastname,"")
        cy.get("button").contains("Save and new").click();
        cy.notificationWithTextIsVisible(Test_firstname2+" "+Test_lastname + " was added")
        cy.get("a[class='menu_people']").last().click( )
        cy.get("tbody").find("tr").contains(Test_firstname2)
        //cy.get("tr").contains(Test_lastname).contains(Test_firstname2).prev()
           // cy.prev()
           // cy.get("tr[data-level='1']").contains(Test_lastname).contains(Test_firstname2).prev()


    })
})
