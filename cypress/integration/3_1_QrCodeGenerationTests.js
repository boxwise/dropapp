describe('QR labels tests - user with rights', () => {

  function typeNumberOfLabels(number){
    cy.get("input[id='field_count']").clear().type(number);
  }

  function labelsCountInputIsVisible(number){
    //cy.get("input[data-testid='numberOfLabelsInput']").should("be.visible");
    cy.get("input[id='field_count']").should("be.visible");
  }

  function clickMakeLabelsButton(){
    cy.get("button").contains("Make labels").click();
  }

  function uncheckBigLabelsCheckbox(){
    cy.get('input[type="checkbox"]').uncheck()
    //cy.get("input[id='field_fulllabel']").click();
  }

  function checkBigLabelsCheckbox(){
    cy.get('input[type="checkbox"]').check()
    //cy.get("input[id='field_fulllabel']").click();
  }

  function isQrsNumberCorrect(numberOfQrs){
    cy.url().should('include', 'count='+numberOfQrs);
  }

  function isUsingBigLabels(){
    cy.get("body").find('embed').should('exist');
  }

  function loginAsUserWithPermissions(){
      
  }

  beforeEach(() => {
    cy.loginAsVolunteer();
    cy.visit('/?action=qr');
  });
  

//   it('Left panel navigation', () => {
//     cy.visit('/');
//     cy.get("a[class='menu_qr']").last().contains("Generate QR labels").click();
//     labelsCountInputIsVisible();
//     cy.verifyActiveSideMenuNavigation('menu_qr');
//   });

//   it('(Desktop) Generate 10 QR codes - small', () => {
//     let numberOfQrs = 10;
//     typeNumberOfLabels(numberOfQrs);
//     uncheckBigLabelsCheckbox();
//     clickMakeLabelsButton();
//     cy.wait(3000);
//     cy.get("body").then($body => {
//         expect($body.find("div[data-testid='boxlabel-small']").length).to.equal(numberOfQrs);
//     });
//   });

//   it('(Desktop) Generate 10 QR codes - big', () => {
//     let numberOfQrs = 1;
//     typeNumberOfLabels(numberOfQrs);
//     checkBigLabelsCheckbox();
//     clickMakeLabelsButton();
//     isQrsNumberCorrect(numberOfQrs);
//     isUsingBigLabels();
//   });

//   it('(iOS) Generate 10 QR codes - small', () => {
//     cy.viewport('iphone-6')
//     let numberOfQrs = 10;
//     typeNumberOfLabels(numberOfQrs);
//     uncheckBigLabelsCheckbox();
//     clickMakeLabelsButton();
//     cy.wait(3000);
//     cy.get("body").then($body => {
//         expect($body.find("div[data-testid='boxlabel-small']").length).to.equal(numberOfQrs);
//     });
//   });

//   it('(iOS) Generate 10 QR codes - big', () => {
//     cy.viewport('iphone-6')
//     let numberOfQrs = 1;
//     typeNumberOfLabels(numberOfQrs);
//     checkBigLabelsCheckbox();
//     clickMakeLabelsButton();
//     isQrsNumberCorrect(numberOfQrs);
//     isUsingBigLabels();
//   });
});

describe('QR labels tests - user without rights', () => {

    function typeNumberOfLabels(number){
        cy.get("input[id='field_count']").clear().type(number);
    }

    function labelsCountInputIsVisible(number){
        //cy.get("input[data-testid='numberOfLabelsInput']").should("be.visible");
        cy.get("input[id='field_count']").should("be.visible");
    }
  
    function clickMakeLabelsButton(){
        cy.get("button").contains("Make labels").click();
    }
  
    function uncheckBigLabelsCheckbox(){
        cy.get('input[type="checkbox"]').uncheck()
        //cy.get("input[id='field_fulllabel']").click();
    }
  
    function checkBigLabelsCheckbox(){
        cy.get('input[type="checkbox"]').check()
        //cy.get("input[id='field_fulllabel']").click();
    }
  
    function isQrsNumberCorrect(numberOfQrs){
        cy.url().should('include', 'count='+numberOfQrs);
    }
  
    function isUsingBigLabels(){
        cy.get("body").find('embed').should('exist');
    }
  
    function loginAsUserWithPermissions(){
        
    }
  
    beforeEach(() => {
        cy.loginAsVolunteer();
        cy.visit('/?action=qr');
    });
    
  
    it('(Desktop) Generate 10 QR codes - small', () => {
        let numberOfQrs = 10;
        typeNumberOfLabels(numberOfQrs);
        uncheckBigLabelsCheckbox();
        clickMakeLabelsButton();
        cy.wait(3000);
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
  
    it('(iOS) Generate 10 QR codes - small', () => {
        cy.viewport('iphone-6')
        let numberOfQrs = 10;
        typeNumberOfLabels(numberOfQrs);
        uncheckBigLabelsCheckbox();
        clickMakeLabelsButton();
        cy.wait(3000);
        cy.get("body").then($body => {
            expect($body.find("div[data-testid='boxlabel-small']").length).to.equal(numberOfQrs);
        });
    });
  
    it('(iOS) Generate 10 QR codes - big', () => {
        cy.viewport('iphone-6')
        let numberOfQrs = 1;
        typeNumberOfLabels(numberOfQrs);
        checkBigLabelsCheckbox();
        clickMakeLabelsButton();
        isQrsNumberCorrect(numberOfQrs);
        isUsingBigLabels();
    });
  });