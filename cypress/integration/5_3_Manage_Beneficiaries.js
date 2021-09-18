// beneficiaries in seed data
const FAMILY1 = "McGregor";
const FAMILY2 = "Tonon";
const FAMILY3 = "Gracie";
const FAMILY_WITHOUT_APPROVAL = "WithoutApproval";
const DEACTIVATED_BENEFICIARY = "DeactivatedBeneficiary";

//beneficiaries created & deleted during the tests
const TEST_FIRSTNAME1 = "Test1";
const TEST_LASTNAME1 = "Test1";
const TEST_FIRSTNAME2 = "Test2";
const TEST_LASTNAME2 = "Test2";
const TEST_FIRSTNAME3 = "Test3";
const TEST_LASTNAME3 = "Test3";
const TEST_CASE_ID = "ManageBeneficiariesTest";


const ITEM_RECOVERED = "Item recovered";
const ITEM_DELETED = "Item deleted";
const BENEFICIARY_DEACTVATE_REQUEST = "do=delete"
const BENEFICIARY_REACTVATE_REQUEST = "do=undelete";
const BENEFICIARY_MERGE_REQUEST = "do=merge";
const BENEFICIARY_MERGE_RESPONSE = null;
const BENEFICIARY_DETACH_REQUEST = "do=detach";
const BENEFICIARY_DETACH_RESPONSE = null;
const BENEFICIARY_DELETE_REQUEST = "do=realdelete"

describe('Manage beneficiaries', () => {

    beforeEach(() => {
        cy.setupAjaxActionHook();
        cy.loginAsVolunteer();
        cy.visit('/?action=people');
    });

    function getBeneficiariesTable(){
        return cy.getElementByTypeAndTestId("table", "table-people");
    }

    function getExportButton(){
        return cy.getElementByTypeAndTestId("button", "exportBeneficiariesButton");
    }
    
    function getNewPersonButton(){
        return cy.get("a").contains("New person");
    }
    
    function getDeactivatedBeneficiariesTab(){
        return cy.get("a").contains("Deactivated");
    }

    function beneficiaryNameIsVisible(familyName){
        cy.get("h1").contains(familyName).should('be.visible'); 
    }

    function beneficiaryDataFormIsVisible(familyName){
        cy.getElementByTypeAndTestId("input", "firstname_id").should('be.visible');
        cy.getElementByTypeAndTestId("input", "lastname_id").should('have.value', familyName);
    }

    function beneficiaryInfoAsideIsVisible(familyName){
        cy.getElementByTypeAndTestId("div", "info-aside").should('be.visible'); 
        // cy.getElementByTypeAndTestId("a", "familyMember").contains(familyName).should('be.visible'); 
        cy.getElementByTypeAndTestId("span", "dropcredit").should('be.visible');
    }

    function selectBeneficiaryFromTableByName(familyName){
        getBeneficiaryRow(familyName).click();
    }

    function getPrivacyDeclarationButton(){
        return cy.get("a[data-testid='privacyDeclarationMissingButton']");
    }

    function getBeneficiaryRow(familyName){
        cy.get('table').should('have.class', 'initialized');
        return cy.getRowWithText(familyName);
    }

    function tableRowIsShowingMissingApprovalIcon(familyName){
        getBeneficiaryRow(familyName).then($familyRow => {
            expect($familyRow.parent().parent().parent().find("i[class='fa fa-edit warning tooltip-this']").length).to.equal(1);
        });
    }

    function addDropsToFamily(tokensCount){
        cy.get("input[data-testid='dropsfamily']").type(tokensCount);
    }

    function clickGiveTokensButton(tokensCount){
        cy.get("button[data-testid='giveTokensListButton']").click();
    }

    function giveTokensPageIsVisible(){
        cy.get("input[data-testid='dropsfamily']").should('be.visible');
        cy.get("input[data-testid='dropsadult']").should('be.visible');
        cy.get("input[data-testid='dropschild']").should('be.visible');
    }

    function selectDeactivatedTab(){
        cy.get("ul[data-testid='listTab'] li a")
            .contains("Deactivated")
            .click();
        cy.url().should('include', 'people_deactivated');
    }

    function selectAllTab() {
        cy.get("ul[data-testid='listTab'] a")
            .contains("All")
            .click();
        cy.url().should('not.include', 'deactivated');
    }

    function selectFilterOption(option){
        cy.get("div[data-testId='filter1Button']").click();
    }

    function createTestBeneficiary(firstname, lastname, testCaseId){
        // navigate to create beneficiary form
        cy.visit('/?action=people_edit&origin=people');
        // fill form and submit
        cy.inputFill("firstname_id", firstname);
        cy.inputFill("lastname_id", lastname);
        cy.inputFill("container_id", testCaseId);
        cy.get("button").contains("Save and close").click();
        cy.url().should('not.include', 'edit');
    }

    function clickDeleteButtonAndCheckConfirmation(){
        cy.getListDeleteButton().click();
        cy.getConfirmActionButton().click();
        cy.waitForAjaxAction(BENEFICIARY_DEACTVATE_REQUEST,ITEM_DELETED);
    }

    function clickMergeButton(){
        cy.get("button[data-testid='mergeToFamily']").click();
        cy.waitForAjaxAction(BENEFICIARY_MERGE_REQUEST,BENEFICIARY_MERGE_RESPONSE);

    }

    function clickRecoverButton(){
        //cy.get("button[data-testid='recoverDeactivatedUser']").click();
        cy.get("button[data-operation='undelete']").click();
        cy.waitForAjaxAction(BENEFICIARY_REACTVATE_REQUEST,ITEM_RECOVERED);
    }

    function clickFullDeleteButton(){
        //cy.get("button[data-testid='fullDeleteUser']").click();
        cy.get("button[data-operation='realdelete']").click();
        cy.getConfirmActionButton().click();
        cy.waitForAjaxAction(BENEFICIARY_DELETE_REQUEST,ITEM_DELETED);

    }

    function verifyBeneficiaryRowLevel(familyName, level){
        getBeneficiaryRow(familyName).then($row=>{
            expect($row.parent().parent().parent().hasClass("level-"+level)).to.eq(true);
        })
    }

    function clickDetachButton(){
        cy.get("button[data-testid='detachFromFamily']").click();
        cy.waitForAjaxAction(BENEFICIARY_DETACH_REQUEST,BENEFICIARY_DETACH_RESPONSE);
    }

    function createMergedFamily(firstname1, lastname1, firstname2, lastname2, testCaseId){
        createTestBeneficiary(firstname1, lastname1, testCaseId);
        createTestBeneficiary(firstname2, lastname2, testCaseId);
        cy.checkGridCheckboxByText(lastname1);
        cy.checkGridCheckboxByText(lastname2);
        clickMergeButton();
    }

    function deleteFromDeactivated(lastname){
        cy.visit('/?action=people_deactivated');
        cy.checkGridCheckboxByText(lastname);
        clickFullDeleteButton();
    }

    function fullDeleteTestedBeneficiary(lastname) {
        cy.visit('/?action=people');
        cy.log('Attempting to delete beneficiary ' + lastname);
        cy.get('body').then(($body) => {
            if ($body.text().includes(lastname)) {
                cy.log("Deleting beneficiary " + lastname)
                cy.checkGridCheckboxByText(lastname)
                clickDeleteButtonAndCheckConfirmation();
                deleteFromDeactivated(lastname);
            }
        });
    }

    function fullDeleteTestedBeneficiaries(whichUsers) {
        whichUsers.forEach(user => fullDeleteTestedBeneficiary(user));
    }

    function fullDeleteOfMergedUsers() {
        cy.visit('/?action=people');
        fullDeleteTestedBeneficiary(TEST_LASTNAME1);
        deleteFromDeactivated(TEST_LASTNAME2);
    }

    it('Navigation, page elements and list visibility', () => {
        cy.visit('/');
        cy.viewport(1280, 720);
        cy.get("a[class='menu_people']").last().contains("Manage beneficiaries").click();
        // page elements visibility checks
        getBeneficiariesTable().should('be.visible');
        getNewPersonButton().should('be.visible');
        getExportButton().should('not.be.visible');
        getDeactivatedBeneficiariesTab().should('be.visible');
        cy.get("h1").contains("Beneficiaries").should('be.visible');
    });

    it('Missing signed approval sign shown in list', () => {
        tableRowIsShowingMissingApprovalIcon(FAMILY_WITHOUT_APPROVAL);
    })

    it('Select beneficiary with privacy declaration', () => {
        selectBeneficiaryFromTableByName(FAMILY1);
        beneficiaryNameIsVisible(FAMILY1);
        beneficiaryDataFormIsVisible(FAMILY1);
        beneficiaryInfoAsideIsVisible(FAMILY1);
        getPrivacyDeclarationButton().should('not.exist');
    });

    it('Select beneficiary without privacy declaration', () => {
        selectBeneficiaryFromTableByName(FAMILY_WITHOUT_APPROVAL);
        beneficiaryDataFormIsVisible(FAMILY_WITHOUT_APPROVAL);
        getPrivacyDeclarationButton().should('be.visible');
    });

    //no cleanup ahead is needed because the delete action doesn't depend on other users and if they're present
    it('Delete beneficiary', () => {
        createTestBeneficiary(TEST_FIRSTNAME1, TEST_LASTNAME1, TEST_CASE_ID);
        getBeneficiaryRow(TEST_LASTNAME1).should('exist');
        cy.checkGridCheckboxByText(TEST_LASTNAME1);
        clickDeleteButtonAndCheckConfirmation();
        //cleanup - full delete of the test user
        selectDeactivatedTab();
        getBeneficiaryRow(TEST_LASTNAME1).should('exist');
        cy.checkGridCheckboxByText(TEST_LASTNAME1);
        clickFullDeleteButton();
    });

    it('Merge beneficiaries into family', () => {
        // if we notice tests start passing because cleanup doesn't work properly, uncomment the next row to maybe try twice
        fullDeleteTestedBeneficiaries([TEST_FIRSTNAME1,TEST_FIRSTNAME2]);
        createTestBeneficiary(TEST_FIRSTNAME1, TEST_LASTNAME1, TEST_CASE_ID);
        createTestBeneficiary(TEST_FIRSTNAME2, TEST_LASTNAME2, TEST_CASE_ID);
        cy.checkGridCheckboxByText(TEST_LASTNAME1);
        cy.checkGridCheckboxByText(TEST_LASTNAME2);
        clickMergeButton();
        verifyBeneficiaryRowLevel(TEST_LASTNAME1,0);
        verifyBeneficiaryRowLevel(TEST_LASTNAME2,1);
        
        //cleanup
        fullDeleteOfMergedUsers();
    });

    it('Detach beneficiaries from family', () => {
        // if we notice tests start passing because cleanup doesn't work properly, uncomment the next row to maybe try twice
        fullDeleteTestedBeneficiaries([TEST_FIRSTNAME1,TEST_FIRSTNAME2]);   //delete beneficiaries from previous tests (should not be any, but just in case)
        createMergedFamily(TEST_FIRSTNAME1, TEST_LASTNAME1, TEST_FIRSTNAME2, TEST_LASTNAME2, TEST_CASE_ID);
        cy.checkGridCheckboxByText(TEST_LASTNAME2);
        clickDetachButton();
        verifyBeneficiaryRowLevel(TEST_LASTNAME1,0);
        verifyBeneficiaryRowLevel(TEST_LASTNAME2,0);
        //cleanup
        fullDeleteTestedBeneficiaries([TEST_FIRSTNAME1,TEST_FIRSTNAME2]);
    });

    // DOESNT WORK YET!
    // it('Give tokens', () => {
    //     cy.checkGridCheckboxByText(FAMILY2);
    //     clickGiveTokensButton();
    //     giveTokensPageIsVisible();
    //     addDropsToFamily(100);
    // });

    it('Load deactivated beneficiaries', () => {
        selectDeactivatedTab();
        getBeneficiaryRow(DEACTIVATED_BENEFICIARY).should('exist');
    });

    it('Recover deactivated beneficiaries', () => {
        //make sure there isn't a beneficiary with the same name in the All tab
        cy.get('body').then(($body) => {
            if ($body.text().includes(TEST_LASTNAME3)) {
                cy.log("found" + TEST_LASTNAME3)
                //delete user from All tab
                cy.checkGridCheckboxByText(TEST_LASTNAME3)
                clickDeleteButtonAndCheckConfirmation();
                //delete user from deactivated tab
                selectDeactivatedTab();
                cy.checkGridCheckboxByText(TEST_LASTNAME3);
                clickFullDeleteButton();
                //navigate to All for the test to start
                selectAllTab();
            }
            //create our test user
            createTestBeneficiary(TEST_FIRSTNAME3, TEST_LASTNAME3, TEST_CASE_ID);
            getBeneficiaryRow(TEST_LASTNAME3).should('exist');
            cy.checkGridCheckboxByText(TEST_LASTNAME3)
            clickDeleteButtonAndCheckConfirmation();
            selectDeactivatedTab();
            cy.checkGridCheckboxByText(TEST_LASTNAME3);
            clickRecoverButton();
            selectAllTab();
            getBeneficiaryRow(TEST_LASTNAME3).should('exist');
            fullDeleteTestedBeneficiaries([TEST_LASTNAME3]);
        });
    });

    // DOESNT WORK YET!
    // it('Filter beneficiaries', () => {
    //     selectFilterOption();
    // });
});