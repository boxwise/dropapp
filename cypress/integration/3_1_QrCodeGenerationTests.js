describe('QR labels tests - user with rights', () => {

    function labelsCountInputIsVisible(number){
        cy.get("input[data-testid='numberOfLabelsInput']").should("be.visible");
    }

    
    function isQrsNumberCorrect(numberOfQrs){
        cy.url().should('include', 'count='+numberOfQrs);
    }

    function isUsingBigLabels(){
        cy.get("body").find('embed').should('exist');
    }

    beforeEach(() => {
        cy.loginAsVolunteer();
        cy.visit('/?action=qr');
    });
    

    it('Left panel navigation', () => {
        cy.visit('/');
        cy.get("a[class='menu_qr']").last().contains("Generate QR labels").click();
        labelsCountInputIsVisible();
        cy.verifyActiveSideMenuNavigation('menu_qr');
    });

    it('(Desktop) Generate 10 QR codes - small', () => {
        let numberOfQrs = 10;
        cy.typeNumberOfLabels(numberOfQrs);
        cy.uncheckBigLabelsCheckbox();
        cy.clickMakeLabelsButton();
        cy.get("div[data-testid='boxlabel-small']").then($smallLabels => {
            expect($smallLabels.length).to.equal(numberOfQrs);
        })
    });
    
    // QRs shown in PDF cause issues when run in CircleCI
    // it('(Desktop) Generate 10 QR codes - big', () => {
    //     let numberOfQrs = 1;
    //     cy.typeNumberOfLabels(numberOfQrs);
    //     cy.checkBigLabelsCheckbox();
    //     cy.clickMakeLabelsButton();
    //     isQrsNumberCorrect(numberOfQrs);
    //     isUsingBigLabels();
    // });

    it('(iPhone 6 viewport) Generate 10 QR codes - small', () => {
        cy.viewport('iphone-6')
        let numberOfQrs = 10;
        cy.typeNumberOfLabels(numberOfQrs);
        cy.uncheckBigLabelsCheckbox();
        cy.clickMakeLabelsButton();
        cy.get("div[data-testid='boxlabel-small']").then($smallLabels => {
            expect($smallLabels.length).to.equal(numberOfQrs);
        })
    });

    // QRs shown in PDF cause issues when run in CircleCI
    // it('(iPhone 6 viewport) Generate 10 QR codes - big', () => {
    //     cy.viewport('iphone-6')
    //     let numberOfQrs = 1;
    //     cy.typeNumberOfLabels(numberOfQrs);
    //     cy.checkBigLabelsCheckbox();
    //     cy.clickMakeLabelsButton();
    //     isQrsNumberCorrect(numberOfQrs);
    //     isUsingBigLabels();
    // });
});

describe('QR labels tests - user without rights', () => {

    function labelsCountInputDoesntExist(number){
        cy.getElementByTypeAndTestId("input", "numberOfLabelsInput").should('not.exist');
    }  

    function generateQrsMenuDoesntExist(){
        cy.get("a[class='menu_qr']").should('not.exist');
    }

    beforeEach(() => {
        cy.loginAsVolunteerWithNoPermissions();
        cy.visit('/?action=qr');
    });
  
    it("'Generate QR labels' menu is hidden", () => {
        generateQrsMenuDoesntExist();
    });
  
    it('Generate QR labels page empty', () => {
        labelsCountInputDoesntExist();
    });    
  });