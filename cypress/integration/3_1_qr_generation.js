context('3.1 QR code generation', () => {
    before(function(){
        cy.LoginAjax(Cypress.env("testAdmin"),Cypress.env("testPwd"),0);
    });

    beforeEach(function(){
        cy.visit('/?action=qr');
    });

    // First test of a test, probably not working yet
    it('Generate 10 QR-codes', ()=>{
        cy.get('#field_count').type('10{enter}');
        cy.get('.content').should(($qr) => {
            expect($qr).to.have.length(11);
        });
    });
});