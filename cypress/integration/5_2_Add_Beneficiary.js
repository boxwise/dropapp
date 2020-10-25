context("5_2_Add_Beneficiary_Test", () => {
    // name starts with aaa to be sure it shows up in the visible part of the table
    let Test_firstname = "aaa_Add beneficiary test";
    let Test_firstname2 = "aaa_Add beneficiary test2";
    let Test_lastname = "aaa_Add beneficiary test";
    let Test_case_id = "IO";


    beforeEach(function () {
        cy.setupAjaxActionHook();
        cy.loginAsVolunteer();
        cy.visit('/?action=people');
    });

    function DeleteTestedBeneficiary(lastname) {
        cy.get('body').then(($body) => {
            if ($body.text().includes(lastname)) {
                cy.log("found " + lastname)
                cy.checkGridCheckboxByText(lastname);
                cy.get("button[data-operation='delete']").click();
                cy.getConfirmActionButton().click();
                // delete the user also from deactivated
                cy.get("ul[data-testid='listTab'] a").contains("Deactivated").click();
                cy.url().should('include', 'people_deactivated');
                cy.checkGridCheckboxByText(lastname);
                cy.get("button").contains("Full delete").click();
                cy.getConfirmActionButton().click();
                cy.waitForAjaxAction("Item deleted");
            }
        })
    }

    function Checktab(Tab_id) {
        cy.get("a[id='" + Tab_id + "']").should("be.visible");
    }

    function CheckAssociation(name, name2) {
        cy.get("tr").contains(name2).parent("td").parent("tr").prev().prev().should('contain', name);
    }

    function CheckLanguageFieldIsEmpty() {
        cy.get("input[id='s2id_autogen3']").should("be.empty");
    }

    function NavigateToEditBeneficiaryForm(){
        cy.visit('/?action=people_edit&origin=people');
    }

    function CheckEmptyBeneficiaryForm() {
        cy.getSelectedValueInDropDown("parent_id").contains("Please select").should('exist');
        cy.checkInputIsEmpty("firstname_id");
        cy.checkInputIsEmpty("lastname_id");
        cy.checkInputIsEmpty("container_id");
        cy.getSelectedValueInDropDown("gender").contains("Please select").should('exist');
        cy.checkInputIsEmpty("date_of_birth_id");
        cy.checkInputIsEmpty("volunteer_id");
        cy.checkInputIsEmpty("registered_id");
        CheckLanguageFieldIsEmpty();
        cy.checkCommentFieldIsEmpty();
        Checktab('tabid_signature');
        cy.getButtonWithText("Save and close").should("be.visible");
        cy.getButtonWithText("Save and new").should("be.visible");
        cy.checkCancelButton();
    }


    function FillForm(firstname, lastname, case_id) {
        cy.inputFill("firstname_id", firstname);
        cy.inputFill("lastname_id", lastname);
        if (case_id != "") {
            cy.inputFill("container_id", case_id);
        }
    }

    it("5_2_1 Fill form, Save and close", () => {
        DeleteTestedBeneficiary(Test_lastname)
        NavigateToEditBeneficiaryForm()
        CheckEmptyBeneficiaryForm();
        //check all the forms 
        FillForm(Test_firstname, Test_lastname, Test_case_id);
        cy.getButtonWithText("Save and close").click();
        cy.notificationWithTextIsVisible(Test_firstname + " " + Test_lastname + " was added");
        cy.getRowWithText(Test_lastname).should('exist');
    });

    it("5_2_2 Prevent empty submit",() => {
        NavigateToEditBeneficiaryForm();
        cy.getButtonWithText("Save and close").click();
        cy.checkQtip("qtip-0-content");
        cy.checkQtip("qtip-1-content");
    });    

    it("5_2_4 Save and New",()=> {
        DeleteTestedBeneficiary(Test_lastname);
        NavigateToEditBeneficiaryForm();
        FillForm(Test_firstname,Test_lastname,Test_case_id);
        cy.getButtonWithText("Save and new").click();
        cy.notyTextNotificationWithTextIsVisible(Test_firstname+" "+Test_lastname + " was added");
        cy.notyTextNotificationWithTextIsVisible(Test_case_id);
        CheckEmptyBeneficiaryForm();
    });

    it("5_2_5 Save and new check if new person in familyhead-dropdown + check if empty",() => {
        DeleteTestedBeneficiary(Test_lastname);
        NavigateToEditBeneficiaryForm();
        FillForm(Test_firstname,Test_lastname,Test_case_id);
        cy.getButtonWithText("Save and new").click();
        cy.notyTextNotificationWithTextIsVisible(Test_firstname+" "+Test_lastname + " was added");
        cy.notyTextNotificationWithTextIsVisible(Test_case_id);
        // Check for the familyhead after adding it above
        cy.selectOptionByText("parent_id",Test_case_id +" "+ Test_firstname); // having an issue checking the dropdown list here
        FillForm(Test_firstname2,Test_lastname,"");
        cy.getButtonWithText("Save and close").click();
        cy.notificationWithTextIsVisible(Test_firstname2+" "+Test_lastname + " was added");
        CheckAssociation(Test_firstname,Test_firstname2);
    });
});

