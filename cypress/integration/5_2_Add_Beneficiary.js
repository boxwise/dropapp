context("5_2_Add_Beneficiary_Test",()=>
{
    let Test_user;
    let Test_passwd;
    let Test_firstname = "John";
    let Test_firstname2 = "Jim";
    let Test_lastname = "Smith";
    let Test_case_id  = "IO";
    let Test_gender = "Female";
    
    before(function() {
        cy.getCoordinatorUser().then(($result) => {
            Test_user = $result.testCoordinator;
            Test_passwd = $result.testPwd;
          });

    })

    beforeEach(function() {
        cy.LoginAjax(Test_user,Test_passwd,true);
        cy.visit('/');
        cy.get("a[class=menu_people]").last().click();
        cy.get("a[class=menu_people_add").last().click();
    })


    function CheckEmptyForm() {
    cy.get("span[id='select2-chosen-1']").contains('Please select').should("be.visible")
    cy.get("input[id = 'field_firstname']").should("be.empty")
    cy.get("input[id = 'field_lastname']").should("be.empty")
    cy.get("input[id='field_container']").should("be.empty")
    cy.get("span[id='select2-chosen-2']").contains('Please select').should("be.visible")
    cy.get("input[id='field_date_of_birth']").should("be.empty")
    cy.get("input[id='s2id_autogen3']").should("be.empty")
    cy.get("textarea[id='field_comments']").should("be.empty")
    cy.get("input[id='field_volunteer']").should("be.empty")
    cy.get("input[id='field_notregistered']").should("be.empty")
    cy.get("a[id='tabid_bicycle']").should("be.visible")
    cy.get("a[id='tabid_signature']").should("be.visible")
    //Check buttons
    cy.get("button").contains("Save and close").should("be.visible")
    cy.get("button").contains("Save and new").should("be.visible")
    cy.get("a").contains("Cancel").should("be.visible")
    }

    function FillForm(firstname,lastname,case_id,gender)
    {cy.get("input[id = 'field_firstname']").type(firstname);
    cy.get("input[id = 'field_lastname']").type(lastname);
    cy.get("input[id='field_container']").should("be.empty")
    cy.get("input[id='field_container']").should("be.empty").then(() => {
        cy.get("input[id='field_container']").type(case_id);
    })
    cy.get("span[id='select2-chosen-2']").click()
    cy.get("input[id='s2id_autogen2_search']").click().type(gender);
    cy.get("div[class='select2-result-label']").first().click();

    }

    it("5_2_1 Fill form, Save and close",() => {
        CheckEmptyForm();
        //check all the forms 
        FillForm(Test_firstname,Test_lastname,Test_case_id,Test_gender);
        cy.get("button").contains("Save and close").click();
        cy.wait(500);
        cy.get("div").contains(Test_firstname+" "+Test_lastname + " was added").should("be.visible");
        cy.get("div").contains(Test_case_id).should("be.visible");

    })
    it("5_2_2 Prevent emtpy submit",() => {

        cy.get("button").contains("Save and close").click();
        cy.get("div[id='qtip-0-content']").should("be.visible");
        cy.get("div[id='qtip-1-content']").should("be.visible");

    })
    it("5_2_3 Prevent emtpy submit from signature-tab",() => {
        cy.get("a[id='tabid_signature']").click();
        cy.get("button").contains("Save and close").click();
        
        //Instead check if tabs are visible;
        cy.get("a[id='tabid_bicycle']").should("be.visible");
        cy.get("a[id='tabid_signature']").should("be.visible");

    })
    it("5_2_4 Save and new check if new person in familyhead-dropdown + check if empty",() => {
        //check all the forms 
        FillForm(Test_firstname,Test_lastname,Test_case_id,Test_gender);
        cy.get("button").contains("Save and new").click();
        cy.wait(500);
        cy.get("div").contains(Test_firstname+" "+Test_lastname + " was added").should("be.visible");
        cy.get("div").contains(Test_case_id).should("be.visible");
        cy.wait(2000);
        // Check for the familyhead after adding it above
        cy.get("span[id='select2-chosen-1']").click();
        cy.get("input[id='s2id_autogen1_search']").click().type(Test_case_id +" "+ Test_firstname);
        cy.get("div[class='select2-result-label']").contains(Test_case_id + " "+ Test_firstname+ " "+ Test_lastname).should("be.visible")
        cy.get("div[class='select2-result-label']").contains(Test_case_id + " "+ Test_firstname+ " "+ Test_lastname).click();
        FillForm(Test_firstname2,Test_lastname,Test_case_id,Test_gender)
        cy.get("button").contains("Save and new").click();
    })

    it("5_2_4 Check Manage beneficiaries",()=>{
        cy.get("a[class='menu_people']").last().click( )
        cy.get("tr[data-level='1").contains(Test_firstname2)


    })
})
