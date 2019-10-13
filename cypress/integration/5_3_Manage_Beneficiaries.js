const FAMILY1 = "McGregor";
const FAMILY2 = "Tonon";
const FAMILY3 = "Gracie";
const FAMILY_WITHOUT_APPROVAL = "WithoutApproval";

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

    function selectBeneficiaryFromTableByName(familyName){
        cy.get("a").contains(familyName).click();
    }

    function beneficiaryNameIsVisible(familyName){
        cy.get("h1").contains(familyName).should('be.visible'); 
    }

    function beneficiaryDataFormIsVisible(familyName){
        cy.get("input[data-testid='firstNameEditInput']").should('be.visible');
        cy.get("input[data-testid='lastNameEditInput']").contains(familyName).should('be.visible');
    }

    function beneficiaryInfoAsideIsVisible(familyName){
        cy.get("div[data-testid='info-aside']").should('be.visible'); 
        cy.get("a[data-testid='familyMember']").contains(familyName).should('be.visible'); 
        cy.get("span[data-testid='dropcredit']").should('be.visible'); 
    }

    function getPrivacyDeclarationButton(){
        return cy.get("a[data-testid='privacyDeclarationMissingButton']");
    }

    function getBeneficiaryRow(familyName){
        return cy.get('tr').contains(familyName);
    }

    function tableRowIsShowingMissingApprovalIcon(familyName){
        cy.get('tr').contains(familyName).then($familyRow => {
            expect($familyRow.parent().parent().parent().find("i[class='fa fa-edit warning tooltip-this']").length).to.equal(1);
        });
    }

    function checkBeneficiaryCheckboxByName(familyName){
        cy.get('tr').contains(familyName).parent().parent().parent().within(() => {
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

    function selectDeactivatedTab(){
        cy.get("ul[data-testid='listTab'] a").contains("Deactivated").click();
        
    }

    function selectAllTab(){
        cy.get("ul[data-testid='listTab'] a").contains("All").click();
    }

    function selectFilterOption(option){
        cy.get("div[data-testId='filter1Button']").click();
    }

    // it('Navigation, page elements and list visibility', () => {
    //     cy.visit('/');
    //     cy.get("a[class='menu_people']").last().contains("Manage beneficiaries").click();
    //     cy.verifyActiveSideMenuNavigation('menu_people');
    //     // page elements visibility checks
    //     getBeneficiariesTable().should('be.visible');
    //     getNewPersonButton().should('be.visible');
    //     getExportButton().should('be.visible');
    //     getDeactivatedBeneficiariesTab().should('be.visible');
    //     cy.get("h1").contains("Beneficiaries").should('be.visible');
    // });

    // it('Missing signed approval sign shown in list', () => {
    //     tableRowIsShowingMissingApprovalIcon(FAMILY_WITHOUT_APPROVAL);
    // })

    // it('Select beneficiary with privacy declaration', () => {
    //     selectBeneficiaryFromTableByName(FAMILY1);
    //     beneficiaryNameIsVisible(FAMILY1);
    //     beneficiaryDataFormIsVisible(FAMILY1);
    //     beneficiaryInfoAsideIsVisible(FAMILY1);
    //     getPrivacyDeclarationButton().should('not.be.visible');
    // });

    // it('Select beneficiary without privacy declaration', () => {
    //     selectBeneficiaryFromTableByName(FAMILY_WITHOUT_APPROVAL);
    //     beneficiaryDataFormIsVisible(FAMILY_WITHOUT_APPROVAL);
    //     getPrivacyDeclarationButton().should('be.visible');
    // });

    // DOESNT WORK YET!
    // it('Give tokens', () => {
    //     checkBeneficiaryCheckboxByName(FAMILY2);
    //     clickGiveTokensButton();
    //     giveTokensPageIsVisible();
    //     addDropsToFamily(100);
    // });

    // DOESNT WORK YET!
    // it('Load deactivated beneficiaries', () => {
    //     selectDeactivatedTab();
    // });

    // DOESNT WORK YET!
    // it('Recover deactivated beneficiaries', () => {
    //     selectDeactivatedTab();
    // });

    // DOESNT WORK YET!
    it('Filter beneficiaries', () => {
        selectFilterOption();
    });
});