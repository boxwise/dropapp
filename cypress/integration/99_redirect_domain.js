import { getLoginConfiguration } from '../config';

describe('Redirect domain', () => {
    let config = getLoginConfiguration();

    it('Navigate to staging.boxwise.co and end up at staging.boxtribute.org', () => {
        cy.visit('https://staging.boxwise.co/?action=stock');
        cy.fillLoginForm()
        cy.url().should('include', 'https://staging.boxtribute.org/?action=stock');
    });

    it('Navigate to staging.boxwise.co and end up at staging.boxtribute.org', () => {
        cy.visit('https://staging.boxwise.co/mobile.php?barcode=test');
        cy.fillLoginForm()
        cy.url().should('include', 'https://staging.boxtribute.org/mobile.php?barcode=test');
    });

    it('Navigate to market.drapenihavet.no and end up at app.boxtribute.org', () => {
        // cy.visit('https://market.drapenihavet.no/mobile.php?barcode=test');
        // // cy.url().should('include', 'app.boxtribute.org');
        // cy.url().should('include', 'barcode=test');
        // cy.url().should('include', 'qrlegacy=1');
    });
});