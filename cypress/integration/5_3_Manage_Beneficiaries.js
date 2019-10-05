const FAMILY1 = "McGregor";
const FAMILY2 = "Tonon";
const FAMILY3 = "Gracie";
const FAMILY_WITHOUT_TOKENS = "Without";

describe('Manage beneficiaries', () => {

    beforeEach(() => {
        cy.loginAsVolunteer();
        cy.visit('/?action=people');
    });

    function getBeneficiariesTable(){
        return cy.get("table[data-testid='table-people']");
    }

    function getExportButton(){
        return cy.get("button").contains("Export");
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

    function beneficiaryDataFormIsVisible(familyName){}

    function beneficiaryInfoAsideIsVisible(familyName){
        cy.get("div[data-testid='info-aside']").should('be.visible'); 
        cy.get("a[data-testid='familyMember']").contains(familyName).should('be.visible'); 
        cy.get("span[data-testid='dropcredit']").should('be.visible'); 
    }

    function getPrivacyDeclarationButton(){
        return cy.get("a[data-testid='privacyDeclarationMissingButton']");
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

    it('Select beneficiary with privacy declaration', () => {
        selectBeneficiaryFromTableByName(FAMILY1);
        beneficiaryNameIsVisible(FAMILY1);
        beneficiaryDataFormIsVisible(FAMILY1);
        beneficiaryInfoAsideIsVisible(FAMILY1);
    });

    it('Select beneficiary without privacy declaration', () => {
        selectBeneficiaryFromTableByName(FAMILY1);
        beneficiaryDataFormIsVisible(FAMILY1);
        getPrivacyDeclarationButton().should('be.visible');
    });
});