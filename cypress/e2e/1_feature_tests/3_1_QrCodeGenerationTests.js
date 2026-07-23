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
        cy.viewport(1280, 720);
        cy.get("a[class='menu_qr']").last().contains("Print Box Labels").click();
        labelsCountInputIsVisible();
        cy.verifyActiveSideMenuNavigation('menu_qr');
    });

    it('Check if QR-Code is generated correctly', () => {
        cy.request({
            method: "POST",
            url: '/?action=qr',
            body: {
                label: "100000000",
            },
            form: true // submit as POST fields not JSON encoded body
        }).then(response => {
            expect(response.status).to.eq(200);
            if(Cypress.config("baseUrl").includes("staging")){
                expect(response.body).to.include("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJYAAACWAQMAAAAGz+OhAAAABlBMVEUEAgT8/vxJvsdeAAAACXBIWXMAAA7EAAAOxAGVKw4bAAABqElEQVRIia2WsY7DMAxDPRjwL3soYA0B9MsaAuhIOe0Nd5PZoGiSF8BORJpyyz/H3ay1kW5teeCMi9Yl5okrDzzxNNDMS2OGm7S5YuEPsw2d8Qe4L77AYiUKUDXQGWpgEwSlZWXfdTll1Mh/j49up4yuCYztMdvkFPCQwqwNw00GRIo2cbwktifA19szBWogMXobDzhNq3pojEOXHW0YLBn7O85ZbIkguy2Udbl1idGKGJxOootAL4nhnblQYq9prpuuMVQUk3iWh/De7SWx0ii4oB2XQPS4wKqO9FBWdSHWJTHmYAy6e/AMw3eJQZtVRTAsF6i+7CWx4OBbcIjUSiOFQZs5q6qTbcW2r84ZM5Hm4Uz0k41bYlgs0Hln2GJvcY1RacQO2gBXIkz5rMtTRqEZEDT5qCZ6iwwhy9ce7KQ7wyTG3GJ8AVOlObvGnk0Ie3KFrGksKyKqJ0MjzMgeILDqoVEZtktgXWLbk5Vhs3Inb40Zx0V2MV9ZhPUFlntP6BU/XWacAt+flWXvfdgp4x6TRWAPwANDD1VYaVTbrlheG5EusX/26D+3gLoUKWVn4QAAAABJRU5ErkJggg==");
            } else if (Cypress.config("baseUrl").includes("localhost")){
                expect(response.body).to.include("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJYAAACWAQMAAAAGz+OhAAAABlBMVEUEAgT8/vxJvsdeAAAACXBIWXMAAA7EAAAOxAGVKw4bAAABWElEQVRIic2Wu2pEMQxE1emX3VmdfllFQJmR74aFdDsOxCxmfQyWrdHjWv8aX/bvWZh5La/dsZwLkWV3xq4d5plcqCw8YSO7sMHFBRa7ud59ieGmnRm2bzAArNfyPrsioyz5Gm+6fcpm0AOj+1tcfcigNX4FQ4VYWmuLDIfvoDObSpm3yKgMInJhFbbssfs5IyTH+QtzuMhyopHiBDyB64uM+WyGfMGMpB4fKKx5ay+Ha+vHBwKbBESAN6/sjw8EVixc0AgORcmpsaEwBDj1ho11tBJZwAaeT45aBigyvv/URDPaUtkEIzBlD4a7znaxzMIHjM9UWZ7mhGtj21tnc3yz5BjLmMyYzKSTiSfGBfYkzRPpmS2y0wMYPewtRyOFTcODSDSEaafKpieftsxsvMLog2JaIwsvMD49fJpzt8r4l929fOR3kU31R3llCSs2PpHd/j79e/YN2aTYi/R0UaUAAAAASUVORK5CYII=");
            }
        });
    });

    it('(Desktop) Generate 250 QR codes - small', () => {
        let numberOfQrs = 250;
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

    // it('(iPhone 6 viewport) Generate 10 QR codes - small', () => {
    //     cy.viewport('iphone-6')
    //     let numberOfQrs = 10;
    //     cy.typeNumberOfLabels(numberOfQrs);
    //     cy.uncheckBigLabelsCheckbox();
    //     cy.clickMakeLabelsButton();
    //     cy.get("div[data-testid='boxlabel-small']").then($smallLabels => {
    //         expect($smallLabels.length).to.equal(numberOfQrs);
    //     })
    // });

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
  
    it("'Print box labels' menu is hidden", () => {
        generateQrsMenuDoesntExist();
    });
  
    it('Print box labels page empty', () => {
        labelsCountInputDoesntExist();
    });    
  });