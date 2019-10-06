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
    function DeleteTested(firstname) {
        cy.get("tr").contains(firstname).parent("td").parent("tr").children().first().children().first().children('label').click()
        cy.get("button[data-operation='delete']").click()
        cy.get("a[data-apply='confirmation']").click()
    }

    function SaveAndProgress(buttonname) {
        cy.get("button").contains(buttonname).click()
    }

    function CheckInput(Field_id){
        cy.get("input[data-testid = '" + Field_id + "']").should("be.empty")
    }
    function Checktab(Tab_id){
        cy.get("a[id='"+ Tab_id + "']").should("be.visible")
    }
    function CheckSaveButton(buttonname){
        cy.get("button").contains(buttonname).should("be.visible")
    }
    function CheckNotyText(Text) {
        cy.get("span[class='noty_text']").contains(Text).should("be.visible");

    }
    function CheckAssociation(name,name2){
        cy.get("tr").contains(name2).parent("td").parent("tr").prev().prev().should('contain',name)
    }

    function CheckCommentField(){
        cy.get("textarea[data-testid='comments_id']").should("be.empty")
    }
    function CheckCancelButton(){
        cy.get("a").contains("Cancel").should("be.visible")
    }
    function CheckLanguageField(){
        cy.get("input[id='s2id_autogen3']").should("be.empty")
    }

    function CheckEmptyForm() {
    cy.getSelectedValueInDropDown("parent_id").contains("Please select").should('exist')
    CheckInput("firstname_id")
    CheckInput("lastname_id")
    CheckInput("container_id")
    cy.getSelectedValueInDropDown("gender").contains("Please select").should('exist')
    CheckInput("date_of_birth_id")
    CheckInput("volunteer_id")
    CheckInput("registered_id")
    CheckLanguageField()
    CheckCommentField()
    Checktab("tabid_bicycle")
    Checktab('tabid_signature')
    CheckSaveButton("Save and close")
    CheckSaveButton("Save and new")
    CheckCancelButton()
    }

    function InputFill( Field_id, Field_input){
    cy.get("input[data-testid = '" + Field_id + "']").type(Field_input)
}
    function FillForm(firstname,lastname,case_id)
    {InputFill("firstname_id",firstname)
    InputFill("lastname_id",lastname)
    if (case_id != ""){
        InputFill("container_id",case_id)
    }
}
    function CheckQtip(qtip_id){
        cy.get("div[id='"+ qtip_id + "']").should("be.visible")

    }


    it("5_2_1 Fill form, Save and close",() => {
        CheckEmptyForm();
        //check all the forms 
        FillForm(Test_firstname,Test_lastname,Test_case_id);
        SaveAndProgress("Save and close")
        cy.notificationWithTextIsVisible(Test_firstname+" "+Test_lastname + " was added")
        DeleteTested(Test_firstname)

    })
    
    it("5_2_2 Prevent emtpy submit",() => {
        SaveAndProgress("Save and close")
        CheckQtip("qtip-0-content")
        CheckQtip("qtip-1-content")
    })
    

    it("5_2_4 Save and new check if new person in familyhead-dropdown + check if empty",() => {
        //check all the forms 
        FillForm(Test_firstname,Test_lastname,Test_case_id);
        SaveAndProgress("Save and new")
        CheckNotyText(Test_firstname+" "+Test_lastname + " was added")
        CheckNotyText(Test_case_id);
        // Check for the familyhead after adding it above
        cy.selectOptionByText("parent_id",Test_case_id +" "+ Test_firstname)
        FillForm(Test_firstname2,Test_lastname,"")
        SaveAndProgress("Save and close")
        cy.notificationWithTextIsVisible(Test_firstname2+" "+Test_lastname + " was added")
        CheckAssociation(Test_firstname,Test_firstname2)
        DeleteTested(Test_firstname)
    })
})

