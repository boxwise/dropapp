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
            debugger;
            expect($familyRow.parent().parent().parent().find("i[data-hasqtip='1']").length).to.equal(1);
        });
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

    it('Missing signed approval sign shown in list', () => {
        tableRowIsShowingMissingApprovalIcon(FAMILY_WITHOUT_APPROVAL);
    })

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

    // it('Delete beneficiary', () => {
    //     selectBeneficiaryFromTableByName(FAMILY_WITHOUT_APPROVAL);
    //     beneficiaryDataFormIsVisible(FAMILY_WITHOUT_APPROVAL);
    //     getPrivacyDeclarationButton().should('be.visible');
    // });
});