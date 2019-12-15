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

describe('Manage beneficiaries', () => {

    beforeEach(() => {
        cy.loginAsVolunteer();
        cy.visit('/?action=people');
    });

    function getBeneficiariesTable(){
        return cy.get("table[data-testid='table-people']");
    }

    function getExportButton(){
        return cy.get("button[data-testid='exportBeneficiariesButton']");
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
        cy.get("input[data-testid='firstname_id']").should('be.visible');
        cy.get("input[data-testid='lastname_id']").should('have.value', familyName);
    }

    function beneficiaryInfoAsideIsVisible(familyName){
        cy.get("div[data-testid='info-aside']").should('be.visible'); 
        cy.get("a[data-testid='familyMember']").contains(familyName).should('be.visible'); 
        cy.get("span[data-testid='dropcredit']").should('be.visible'); 
    }

    function selectBeneficiaryFromTableByName(familyName){
        getBeneficiaryRow(familyName).click();
    }

    function getPrivacyDeclarationButton(){
        return cy.get("a[data-testid='privacyDeclarationMissingButton']");
    }

    function getBeneficiaryRow(familyName){
        return cy.get('tr').contains(familyName);
    }

    function tableRowIsShowingMissingApprovalIcon(familyName){
        getBeneficiaryRow(familyName).then($familyRow => {
            expect($familyRow.parent().parent().parent().find("i[class='fa fa-edit warning tooltip-this']").length).to.equal(1);
        });
    }

    function checkBeneficiaryCheckboxByName(familyName){
        getBeneficiaryRow(familyName).parent().parent().parent().within(() => {
            cy.get("input[type='checkbox']").check();
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

    function getDeactivatedTab(){
        return cy.get("ul[data-testid='listTab'] a").contains("Deactivated");
    }

    function selectAllTab(){
        cy.get("ul[data-testid='listTab'] a").contains("All").click();
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
    }

    function clickDeleteButton(){
        cy.get("button[data-testid='list-delete-button']").click();
    }

    function confirmAction(){
        cy.get("a[data-apply='confirmation']").click();
    }

    function clickMergeButton(){
        cy.get("button[data-testid='mergeToFamily']").click();
    }

    function clickRecoverButton(){
        //cy.get("button[data-testid='recoverDeactivatedUser']").click();
        cy.get("button[data-operation='undelete']").click();
    }

    function clickFullDeleteButton(){
        //cy.get("button[data-testid='fullDeleteUser']").click();
        cy.get("button[data-operation='realdelete']").click();
    }

    function verifyBeneficiaryRowLevel(familyName, level){
        getBeneficiaryRow(familyName).then($row=>{
            expect($row.parent().parent().parent().hasClass("level-"+level)).to.eq(true);
        })
    }

    function clickDetachButton(){
        cy.get("button[data-testid='detachFromFamily']").click();
    }

    function createMergedFamily(firstname1, lastname1, firstname2, lastname2, testCaseId){
        createTestBeneficiary(firstname1, lastname1, testCaseId);
        createTestBeneficiary(firstname2, lastname2, testCaseId);
        cy.reload();
        checkBeneficiaryCheckboxByName(lastname1);
        checkBeneficiaryCheckboxByName(lastname2);
        clickMergeButton();
    }

    function deleteFromDeactivated(lastname){
        cy.visit('/?action=people_deactivated');
        checkBeneficiaryCheckboxByName(lastname);
        clickFullDeleteButton();
        confirmAction();
    }

    function fullDeleteTestedBeneficiary(lastname) {
        cy.get('body').then(($body) => {
            if ($body.text().includes(lastname)) {
                cy.log("found" + lastname)
                checkBeneficiaryCheckboxByName(lastname)
                clickDeleteButton();
                confirmAction();
                deleteFromDeactivated(lastname);
            }
        });
    }

    function fullDeleteTestedBeneficiaries(whichUsers){
        cy.visit('/?action=people');
        if (whichUsers.includes(TEST_LASTNAME1)) {
            fullDeleteTestedBeneficiary(TEST_LASTNAME1);
            cy.visit('/?action=people');
        }
        if (whichUsers.includes(TEST_LASTNAME2)) {
            fullDeleteTestedBeneficiary(TEST_LASTNAME2);
            cy.visit('/?action=people');
        }
        if (whichUsers.includes(TEST_LASTNAME3)) {
            fullDeleteTestedBeneficiary(TEST_LASTNAME3);
            cy.visit('/?action=people');
        }
    }

    function fullDeleteOfMergedUsers() {
        cy.visit('/?action=people');
        fullDeleteTestedBeneficiary(TEST_LASTNAME1);
        deleteFromDeactivated(TEST_LASTNAME2);
    }

    it('Navigation, page elements and list visibility', () => {
        cy.visit('/');
        cy.get("a[class='menu_people']").last().contains("Manage beneficiaries").click();
        cy.verifyActiveSideMenuNavigation('menu_people');
        // page elements visibility checks
        getBeneficiariesTable().should('be.visible');
        getNewPersonButton().should('be.visible');
        getExportButton().should('be.visible');
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
        getPrivacyDeclarationButton().should('not.be.visible');
    });

    it('Select beneficiary without privacy declaration', () => {
        selectBeneficiaryFromTableByName(FAMILY_WITHOUT_APPROVAL);
        beneficiaryDataFormIsVisible(FAMILY_WITHOUT_APPROVAL);
        getPrivacyDeclarationButton().should('be.visible');
    });

    //no cleanup ahead is needed because the delete action doesn't depend on other users and if they're present
    it('Delete beneficiary', () => {
        createTestBeneficiary(TEST_FIRSTNAME1, TEST_LASTNAME1, TEST_CASE_ID);
        cy.reload();
        checkBeneficiaryCheckboxByName(TEST_LASTNAME1);
        clickDeleteButton();
        confirmAction();
        getDeactivatedTab().click();
        getBeneficiaryRow(TEST_LASTNAME1).should('exist');

        //cleanup - full delete of the test user
        checkBeneficiaryCheckboxByName(TEST_LASTNAME1);
        clickFullDeleteButton();
        confirmAction();
    });

    it('Merge beneficiaries into family', () => {
        // if we notice tests start passing because cleanup doesn't work properly, uncomment the next row to maybe try twice
        // fullDeleteTestedBeneficiaries([TEST_FIRSTNAME1,TEST_FIRSTNAME2]);
        createTestBeneficiary(TEST_FIRSTNAME1, TEST_LASTNAME1, TEST_CASE_ID);
        createTestBeneficiary(TEST_FIRSTNAME2, TEST_LASTNAME2, TEST_CASE_ID);
        cy.reload();
        checkBeneficiaryCheckboxByName(TEST_LASTNAME1);
        checkBeneficiaryCheckboxByName(TEST_LASTNAME2);
        clickMergeButton();
        cy.reload();
        verifyBeneficiaryRowLevel(TEST_LASTNAME1,0);
        verifyBeneficiaryRowLevel(TEST_LASTNAME2,1);
        
        //cleanup
        fullDeleteTestedBeneficiaries([TEST_FIRSTNAME1,TEST_FIRSTNAME2]);
    });

    it('Detach beneficiaries from family', () => {
        // if we notice tests start passing because cleanup doesn't work properly, uncomment the next row to maybe try twice
        // fullDeleteTestedBeneficiaries([TEST_FIRSTNAME1,TEST_FIRSTNAME2]);   //delete beneficiaries from previous tests (should not be any, but just in case)
        createMergedFamily(TEST_FIRSTNAME1, TEST_LASTNAME1, TEST_FIRSTNAME2, TEST_LASTNAME2, TEST_CASE_ID);
        cy.reload();
        checkBeneficiaryCheckboxByName(TEST_LASTNAME2);
        clickDetachButton();
        cy.reload();
        verifyBeneficiaryRowLevel(TEST_LASTNAME1,0);
        verifyBeneficiaryRowLevel(TEST_LASTNAME2,0);
        //cleanup
        fullDeleteTestedBeneficiaries([TEST_FIRSTNAME1,TEST_FIRSTNAME2]);
    });

    // DOESNT WORK YET!
    // it('Give tokens', () => {
    //     checkBeneficiaryCheckboxByName(FAMILY2);
    //     clickGiveTokensButton();
    //     giveTokensPageIsVisible();
    //     addDropsToFamily(100);
    // });

    it('Load deactivated beneficiaries', () => {
        getDeactivatedTab().click();
        getDeactivatedTab().should('have.class', 'active');
        getBeneficiaryRow(DEACTIVATED_BENEFICIARY).should('exist');
    });

    it('Recover deactivated beneficiaries', () => {
        //make sure there isn't a beneficiary with the same name in the All tab
        cy.get('body').then(($body) => {
            if ($body.text().includes(TEST_LASTNAME3)) {
                cy.log("found" + TEST_LASTNAME3)
                //delete user from All tab
                checkBeneficiaryCheckboxByName(TEST_LASTNAME3)
                clickDeleteButton();
                confirmAction();
                //delete user from deactivated tab
                getDeactivatedTab().click();
                checkBeneficiaryCheckboxByName(TEST_LASTNAME3);
                clickFullDeleteButton();
                confirmAction();
                //navigate to All for the test to start
                selectAllTab();
            }
            //create our test user
            createTestBeneficiary(TEST_FIRSTNAME3, TEST_LASTNAME3, TEST_CASE_ID);
            cy.reload();
            checkBeneficiaryCheckboxByName(TEST_LASTNAME3)
            clickDeleteButton();
            confirmAction();
            getDeactivatedTab().click();
            checkBeneficiaryCheckboxByName(TEST_LASTNAME3);
            clickRecoverButton();
            cy.notyTextNotificationWithTextIsVisible("Item recovered");
            selectAllTab();
            getBeneficiaryRow(TEST_LASTNAME3).should('exist');

            //cleanup
            fullDeleteTestedBeneficiaries([TEST_LASTNAME3]);
        });
    });

    // DOESNT WORK YET!
    // it('Filter beneficiaries', () => {
    //     selectFilterOption();
    // });
});