describe('QR labels tests - user with rights', () => {

    function typeNumberOfLabels(number){
        cy.get("input[id='field_count']").clear().type(number);
    }

    function labelsCountInputIsVisible(number){
        cy.get("input[data-testid='numberOfLabelsInput']").should("be.visible");
    }

    function clickMakeLabelsButton(){
        cy.get("button").contains("Make labels").click();
    }

    function uncheckBigLabelsCheckbox(){
        cy.get("input[data-testid='field_fulllabel']").uncheck();
    }

    function checkBigLabelsCheckbox(){
        cy.get("input[data-testid='field_fulllabel']").check();
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
        typeNumberOfLabels(numberOfQrs);
        uncheckBigLabelsCheckbox();
        clickMakeLabelsButton();
        cy.wait(4000);  //no idea how to wait for QRs being generated
        cy.get("body").then($body => {
            expect($body.find("div[data-testid='boxlabel-small']").length).to.equal(numberOfQrs);
        });
    });

    it('(Desktop) Generate 10 QR codes - big', () => {
        let numberOfQrs = 1;
        typeNumberOfLabels(numberOfQrs);
        checkBigLabelsCheckbox();
        clickMakeLabelsButton();
        isQrsNumberCorrect(numberOfQrs);
        isUsingBigLabels();
    });

    it('(iPhone 6 viewport) Generate 10 QR codes - small', () => {
        cy.viewport('iphone-6')
        let numberOfQrs = 10;
        typeNumberOfLabels(numberOfQrs);
        uncheckBigLabelsCheckbox();
        clickMakeLabelsButton();
        cy.wait(4000);  //no idea how to wait for QRs being generated
        cy.get("body").then($body => {
            expect($body.find("div[data-testid='boxlabel-small']").length).to.equal(numberOfQrs);
        });
    });

    it('(iPhone 6 viewport) Generate 10 QR codes - big', () => {
        cy.viewport('iphone-6')
        let numberOfQrs = 1;
        typeNumberOfLabels(numberOfQrs);
        checkBigLabelsCheckbox();
        clickMakeLabelsButton();
        isQrsNumberCorrect(numberOfQrs);
        isUsingBigLabels();
    });
});

describe('QR labels tests - user without rights', () => {

    function labelsCountInputDoesntExist(number){
        cy.get("input[data-testid='numberOfLabelsInput']").should('not.exist');
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

    // Doesn't work even with user who has rights to generate QR
    // it('Request to generate QR codes rejected', () => {
    //     cy.visit('/?action=qr');
    //     cy.request({
    //         method: "POST",
    //         url: '/?action=qr',
    //         body: {
    //             __fullabel: "checkbox",
    //             fullabel: 1,
    //             __count: "text",
    //             count: "2"
    //         },
    //         form: true
    //     }).then(response => {
    //         debugger;
    //         // ?
    //     });
    // });
    
  });